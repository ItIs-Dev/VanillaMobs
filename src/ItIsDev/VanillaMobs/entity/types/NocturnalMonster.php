<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity\types;

use ItIsDev\VanillaMobs\entity\Monster;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class NocturnalMonster extends Monster {

	protected function moveTo(Vector3 $pos, float $acceleration = 1) : void {
		if(!$this->canMove) return;

		$world = $this->getWorld();

        $time = $world->getTimeOfDay();

		$inv = $this->getArmorInventory();
        $groundY = $this->getWorld()->getHighestBlockAt((int)$this->getPosition()->getX(), (int)$this->getPosition()->getZ());
		
		$isInShade = $this->getPosition()->getY() < $groundY;
		
		if($time < 12542 && $inv->getHelmet()->isNull()) {
            if($this->target instanceof Player){
                $targetGroundY = $world->getHighestBlockAt((int)$this->target->getPosition()->getX(), (int)$this->target->getPosition()->getZ());
                $targetInShade = $this->target->getPosition()->getY() < $targetGroundY;
                
                if($targetInShade){
                    $dir = $pos->subtractVector($this->getPosition())->normalize();
                    $step = $dir->multiply($this->getSpeed() * $acceleration);
                    $this->move($step->x, 0, $step->z);
                    $this->lookAt($this->target->getPosition());
                    return;
                }else{
                    $this->lookAt($this->target->getPosition());
                }
            }

			if($isInShade){
                $pos = $this->getPosition();
                $this->point = $pos;
                $this->lookAt($pos);
			}else{
				for($i = 0; $i < 10; $i++){
					$xr = mt_rand(-$i, $i);
					$zr = mt_rand(-$i, $i);
					$checkX = (int)$this->getPosition()->getX() + $xr;
					$checkZ = (int)$this->getPosition()->getZ() + $zr;
					
					$highest = $this->getWorld()->getHighestBlockAt($checkX, $checkZ);
					if($this->getPosition()->getY() < $highest){ 
						$pos = new Vector3($checkX, $this->getPosition()->getY(), $checkZ);
						$this->point = $pos;
                        $this->location->pitch = 0;
						break;
					}
				}
			}
		}

        if($this->target instanceof Player) $this->lookAt($this->target->getPosition());

		$dir = $pos->subtractVector($this->getPosition())->normalize();
		$step = $dir->multiply($this->getSpeed() * $acceleration);
		$this->move($step->x, 0, $step->z);
	}
}