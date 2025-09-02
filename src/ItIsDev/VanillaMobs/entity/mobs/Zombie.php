<?php

declare(strict_types = 1);

namespace ItIsDev\VanillaMobs\entity\mobs;

use ItIsDev\VanillaMobs\entity\trait\WearArmor;
use ItIsDev\VanillaMobs\entity\types\NocturnalMonster;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Zombie extends NocturnalMonster {

	use WearArmor;

	protected bool $isBaby;

	public float $damageAttack = 3;

	public function __construct(Location $location, ?CompoundTag $nbt = null, bool $isBaby = false) {
		parent::__construct($location, $nbt);
		$this->isBaby = $isBaby;

		if($isBaby) $this->setScale(0.5);

		$this->randomArmor();
	}

	public function getName() : string {
		return "Zombie";
	}

	public static function getNetworkTypeId() : string {
		return EntityIds::ZOMBIE;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(1.8, 0.6);
	}

	public function getDrops() : array {
		$drops = [
			VanillaItems::ROTTEN_FLESH()->setCount(mt_rand(0, 2))
		];

		if (mt_rand(0, 199) < 5) {
			switch (mt_rand(0, 2)) {
				case 0:
					$drops[] = VanillaItems::CARROT();
					break;
				case 1:
					$drops[] = VanillaItems::POTATO();
					break;
				case 2:
					$drop[] = VanillaItems::IRON_INGOT();
					break;
			}
		}

		return $drops;
	}

	public function getXpDropAmount() : int {
		return 5;
	}
}