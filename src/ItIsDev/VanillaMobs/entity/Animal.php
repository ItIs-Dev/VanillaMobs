<?php

declare(strict_types=1);


namespace ItIsDev\VanillaMobs\entity;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;

class Animal extends Entity {

    protected $speed = 0.2;

    protected ?Vector3 $point = null;
    protected int $errorMove = 0;

    protected int $randomMoveTick = 0;
    protected int $randomMoveTickTime = 50;

    protected bool $panic = false;
    protected int $panicTick = 0;
    protected float $panicAcceleration = 3;

    public function attack(EntityDamageEvent $source) : void {
        parent::attack($source);

        $this->randomMoveTick = 0;
        $this->panicTick = mt_rand(60, 100);
        $this->panic = true;
    }

    public function entityBaseTick(int $tickDiff = 1): bool {
        $hasUpdate = parent::entityBaseTick($tickDiff);

        if(!$this->isAlive()) {
            return $hasUpdate;
        }
        
        if($this->panicTick > 0 and $this->panic === true) {
            if(mt_rand(1, 10) <= 5) {
                $xr = mt_rand(-10,10);
                $zr = mt_rand(-10, 10);
                $pos =$this->getPosition()->add($xr, 1, $zr);
                $this->point = $pos;
            }
            $this->panicTick--;
        }

        if($this->panicTick <= 0 and $this->panic === true) $this->panic = false;

        if($this->errorMove >= 20) {
            $this->point = null;
            $this->errorMove = 0;
        }

        $this->randomMoveTick++;
        if($this->randomMoveTick >= $this->randomMoveTickTime and $this->point === null) {
            $xr = mt_rand(-10,10);
            $zr = mt_rand(-10, 10);
            $pos =$this->getPosition()->add($xr, 1, $zr);
            $this->point = $pos;
            $this->randomMoveTick = 0;
        }

        $point = $this->point;
        if($point !== null) {

            $acceleration = 1;

            if($this->panic == true) $acceleration = $this->panicAcceleration;
            $this->moveTo($point, $acceleration);
            $this->lookAt($point);
            $this->checkBlock();

            $motion = $this->getMotion();
            if($motion->x <= 0.1 or $motion->y <= 0.1 or $motion->z <= 0.1){
                $this->errorMove++;
            }else{
                $this->errorMove = 0;
            }

            if($this->getPosition()->distance($point) <= 0.1) {
                $this->point = null;
            }
        }
        return $hasUpdate;
    }
}