<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;
use ItIsDev\VanillaMobs\entity\Entity as BaseEntity;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\math\Vector3;

class Monster extends BaseEntity {

    protected ?Entity $target = null;
    public float $damageAttack = 2;
    protected $speed = 0.2;

    protected float $attackDistance = 1;
    protected float $unTargetDistance = 10;
    protected float $findTargetDistance = 10;

    protected bool $toPoint = false;
    protected array $pointToMove = [];
    protected ?Vector3 $point = null;
    protected int $errorMove = 0;

    protected int $randomMoveTick = 0;

    protected int $randomMoveTickTime = 100; 

    public function setTarget(Entity $entity): void {
         $this->target = $entity;
    }

    public function getTarget(): ?Entity {
        return $this->target;
    }

    public function unTarget(): void {
        $this->target = null;
    }

    public function getDamageAttack(): float {
        return $this->damageAttack;
    }

    public function findTarget(): ?Entity {
        $this->randomMoveTick++;
        if($this->randomMoveTick >= $this->randomMoveTickTime) {
            $xr = mt_rand(-10,10);
            $zr = mt_rand(-10, 10);
            $pos =$this->getPosition()->add($xr, 1, $zr);
            $this->point = $pos;
            $this->randomMoveTick = 0;
        }

        if($this->point !== null) {
            $this->moveTo($this->point);
            $this->lookAt($this->point);
            $this->location->pitch = 0;
            $this->checkBlock();
        }

        $near = null;
        $nearDistance = $this->findTargetDistance;

        foreach($this->getWorld()->getEntities() as $entity) {
            if($entity instanceof Player) {
                $distance = $this->getPosition()->distance($entity->getPosition());
                if($distance < $nearDistance) {
                    $near = $entity;
                }
            }
        }

        return $near;
    }

    public function attackTarget(): void {
        $target = $this->getTarget();
        if($target === null) return;

        if(!$target->isAlive()) {
            $this->pointToMove = [];
            $this->unTarget();
            return;
        }

        $distance = $this->getPosition()->distance($target->getPosition());
        if($distance > $this->unTargetDistance) {
            $this->pointToMove = [];
            $this->unTarget();
            return;
        }

        if($distance <= $this->attackDistance) {
            $this->lookAt($target->getPosition());
            $this->pointToMove = [];

            if($this->canMove) $this->setCanMove(false);

            $target->attack(new EntityDamageByEntityEvent(
                $this, $target,
                EntityDamageEvent::CAUSE_ENTITY_ATTACK,
                $this->getDamageAttack()
            ));
            return;
        } else {
            if(!$this->canMove) $this->setCanMove(true);
        }
    }

    public function moveToPoint(): void {
        if($this->pointToMove === []) {
            $this->toPoint = false;
            return;
        }

        $point = array_shift($this->pointToMove);
        $this->point = $point;
        $this->lookAt($point);
    }


    public function moveToAttack(): void {
        $target = $this->getTarget();
        if($target === null) return;
        
        $targetPos = $target->getPosition();

        if(!$this->toPoint) {
            $this->toPoint = true;
            $this->moveToPoint();
        }
        $this->checkBlock();
        $this->attackTarget();
        if($this->getPosition()->distance($targetPos) <= 2 and $targetPos->getY() <= $this->getPosition()->getY() + 2) {
            $this->location->pitch = 0;
        }
    }

    public function entityBaseTick(int $tickDiff = 1): bool {
        $hasUpdate = parent::entityBaseTick($tickDiff);

        if(!$this->isAlive()) {
            return $hasUpdate;
        }

        if($this->errorMove >= 20) {
            $this->pointToMove = [];
            $this->errorMove = 0;
            $this->toPoint = false;
        }

        if($this->toPoint) {
            $point = $this->point;
            $this->moveTo($point);
            $motion = $this->getMotion();
            if($motion->x <= 0.1 or $motion->y <= 0.1 or $motion->z <= 0.1){
                $this->errorMove++;
            }else{
                $this->errorMove = 0;
            }
            if($this->getPosition()->distance($point) <= 0.1) {
                $this->toPoint = false;
            }
        }
        
        if($this->getTarget() !== null) {
            $target = $this->getTarget();
            $targetPos = $target->getPosition()->floor();
            if($this->getPosition()->distance($targetPos) > $this->attackDistance) {
                if($target instanceof Player) {
                    if($target->isFlying()) {
                        $this->moveTo($targetPos);
                        $this->lookAt($targetPos);
                    }else{
                        $this->pointToMove[] = $targetPos;
                    }
                }else{
                    $this->pointToMove[] = $targetPos;
                }
            }
        }

        if($this->getTarget() === null) {
            $target = $this->findTarget();
            if($target !== null) {
                $this->setTarget($target);
            }
        } else {
            $this->moveToAttack();
        }
        return $hasUpdate;
    }
}