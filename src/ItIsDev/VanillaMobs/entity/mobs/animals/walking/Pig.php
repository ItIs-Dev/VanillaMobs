<?php

declare(strict_types = 1);

namespace ItIsDev\VanillaMobs\entity\mobs\animals\walking;

use ItIsDev\VanillaMobs\entity\Animal;
use ItIsDev\VanillaMobs\entity\trait\Baby;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Pig extends Animal {

	use Baby;

	protected bool|int $isBaby;

	public function __construct(Location $location, ?CompoundTag $nbt = null) {
		parent::__construct($location, $nbt);

		$this->Baby();
	}

	public function initEntity(CompoundTag $nbt): void {
		parent::initEntity($nbt);

		$this->isBaby = $nbt->getByte("isBaby", 0);
	}

	public function getName() : string {
		return "Pig";
	}

	public static function getNetworkTypeId() : string {
		return EntityIds::PIG;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(1.8, 0.6);
	}

	public function getDrops() : array {
		$drops = [
			VanillaItems::RAW_PORKCHOP()->setCount(mt_rand(1, 3))
		];

		return $drops;
	}

	public function getXpDropAmount() : int {
		return mt_rand(1, 3);
	}

	
}