<?php

declare(strict_types=1);


namespace ItIsDev\VanillaMobs\entity;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;

class Animal extends Entity {

    protected float $speed = 0.2;

    protected ?Vector3 $point = null;
    protected int $errorMove = 0;

    protected int $randomMoveTick = 0;
    protected int $randomMoveTickTime = 50;

    protected bool $panic = false;
    protected int $panicTick = 0;
    protected float $panicAcceleration = 1;

    protected function getRandomPoint(): Vector3 {
        $xr = mt_rand(-10, 10);
        $zr = mt_rand(-10, 10);
        return $this->getPosition()->add($xr, 1, $zr);
    }

    public function attack(EntityDamageEvent $source) : void {
        parent::attack($source);

        $this->randomMoveTick = 0;
        $this->panicTick = mt_rand(60, 100);
        $this->panic = true;
    }

        public function entityBaseTick(int $tickDiff = 1): bool {
        $hasUpdate = parent::entityBaseTick($tickDiff);
        if(!$this->isAlive()) return $hasUpdate;

        if($this->panic && $this->panicTick > 0) {
            if(mt_rand(1, 100) <= 10) {
                $this->point = $this->getRandomPoint();
            }
            $this->panicTick--;
        } elseif($this->panicTick <= 0) {
            $this->panic = false;
        }

        if($this->errorMove >= 20) {
            $this->point = null;
            $this->errorMove = 0;
        }

        if($this->randomMoveTick++ >= $this->randomMoveTickTime && $this->point === null) {
            $this->point = $this->getRandomPoint();
            $this->randomMoveTick = 0;
        }

        if($this->point !== null) {
            $acceleration = $this->panic ? $this->panicAcceleration : 1;
            $this->moveTo($this->point, $acceleration);
            $this->lookAt($this->point);
            $this->checkBlock();

            $motion = $this->getMotion();
            if($motion->x <= 0.01 && $motion->z <= 0.01) {
                $this->errorMove++;
            } else {
                $this->errorMove = 0;
            }

            if($this->getPosition()->distance($this->point) <= 0.1) {
                $this->point = null;
            }
        }

        return $hasUpdate;
    }

}