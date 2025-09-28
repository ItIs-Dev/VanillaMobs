<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity\trait;

trait Baby {

    public function Baby(): void {
        if(mt_rand(0, 100) < 20) {
            $this->setScale(0.5);
            $this->isBaby = true;
        }else{
            $this->isBaby = false;
        }
    }
}