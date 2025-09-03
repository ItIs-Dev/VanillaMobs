<?php

declare(strict_types = 1);

namespace ItIsDev\VanillaMobs\entity\mobs\animals\walking;

use ItIsDev\VanillaMobs\entity\Animal;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Sheep extends Animal {

	protected bool $isBaby;

	public function __construct(Location $location, ?CompoundTag $nbt = null, bool $isBaby = false) {
		parent::__construct($location, $nbt);
		$this->isBaby = $isBaby;

		if($isBaby) $this->setScale(0.5);
	}

	public function getName() : string {
		return "Sheep";
	}

	public static function getNetworkTypeId() : string {
		return EntityIds::SHEEP;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(1.8, 0.6);
	}

	public function getDrops() : array {
		$drops = [
			VanillaItems::RAW_MUTTON()->setCount(mt_rand(1, 2)),
            StringToItemParser::getInstance()->parse("white_wool")->setCount(mt_rand(1, 2))
		];

		return $drops;
	}

	public function getXpDropAmount() : int {
		return mt_rand(1, 3);
	}
}