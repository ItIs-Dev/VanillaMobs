<?php

declare(strict_types = 1);

namespace ItIsDev\VanillaMobs\entity\mobs\monster\walking;

use ItIsDev\VanillaMobs\entity\interfaces\Wearable;
use ItIsDev\VanillaMobs\entity\Monster;
use ItIsDev\VanillaMobs\entity\trait\Baby;
use ItIsDev\VanillaMobs\entity\trait\Nocturnal;
use ItIsDev\VanillaMobs\entity\trait\WearArmor;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Zombie extends Monster implements Wearable {

	use Nocturnal;
	use WearArmor;
	use Baby;

	protected bool|int $isBaby;

	public float $damageAttack = 3;

	protected float $unTargetDistance = 35;
    protected float $findTargetDistance = 20;

	public function __construct(Location $location, ?CompoundTag $nbt = null) {
		parent::__construct($location, $nbt);
		
		$this->Baby();
	}

	public function initEntity(CompoundTag $nbt): void {
		parent::initEntity($nbt);

		if(!$this->armorExisted) $this->randomArmor();
		$this->isBaby = $nbt->getByte("isBaby", 0);
		$this->armorExisted = $nbt->getByte("armorExisted", 0);
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

	public function entityBaseTick(int $tickDiff = 1): bool {
		$inv = $this->getArmorInventory();
		if($inv->getHelmet() == VanillaItems::AIR()) $this->fireOnDay(3, 0.5);
		
		return parent::entityBaseTick();
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
		if($this->isBaby) return 10;
		return 5;
	}

	public function saveNBT(): CompoundTag {
		$nbt = parent::saveNBT();

		$nbt->setByte("isBaby", (int)$this->isBaby);
		$nbt->setByte("armorExisted", (int)$this->armorExisted);

		return $nbt;
	}
}