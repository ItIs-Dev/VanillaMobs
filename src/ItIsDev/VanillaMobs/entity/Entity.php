<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity;

use pocketmine\block\Block;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

class Entity extends Living {

	protected bool $canMove = true;

    protected float $speed = 1;

    private const JUMP_FORCE = 0.42;

	public function getCanMove(): bool {
		return $this->canMove;
	}

	public function setCanMove(bool $canMove): void {
		$this->canMove = $canMove;
	}

    public function getSpeed(): float {
        return $this->speed;
    }

    public function setSpeed(float $v): void {
        $this->speed = $v;
    }

    public function initEntity(CompoundTag $nbt) : void {
		parent::initEntity($nbt);

		$this->setHasGravity(true);
	}

    public function getName(): string {
        return "Entity";
    }

    public static function getNetworkTypeId(): string {
        return "minecraft:generic_mob";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.0, 1.0);
    }

    public function getDrops(): array {
        return [];
    }

    protected function moveTo(Vector3 $pos, float $acceleration = 1) : void {
		if(!$this->canMove) return;

		$dir = $pos->subtractVector($this->getPosition())->normalize();

		$step = $dir->multiply($this->getSpeed() * $acceleration);
		
		$this->move($step->x, 0, $step->z);
	}

	public function jump(): void {
        $below = $this->getWorld()->getBlock($this->getPosition()->subtract(0, 1, 0));
        if($below->isFullCube()){ 
            $this->setMotion(new Vector3(  
            $this->motion->x,
            self::JUMP_FORCE,
            $this->motion->z));
        }
    }

    public function getBlockDir(float $os, float $y): Block {
        $direction = $this->getDirectionVector()->multiply($os);
        $frontPos = $this->getPosition()->add($direction->x, $y, $direction->z);
        $block = $this->getWorld()->getBlock($frontPos->floor());
        return $block;
    }

    public function hasFrontBlock(float $y): bool {
        return $this->getBlockDir(1, $y)->isFullCube();
    }
    
    public function checkBlock(): void {
        $frontFoot = $this->hasFrontBlock(0);
        $frontHead = $this->hasFrontBlock(1);
        
        if($frontFoot && !$frontHead) {
            $this->jump();
            return;
        }
    }
}