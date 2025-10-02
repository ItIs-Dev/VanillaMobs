<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity;

use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\CustomiesItemFactory;
use ItIsDev\VanillaMobs\entity\mobs\animals\walking\Pig;
use ItIsDev\VanillaMobs\entity\mobs\animals\walking\Sheep;
use ItIsDev\VanillaMobs\entity\mobs\monster\walking\Zombie;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemTypeIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\world\World;

class MobsManager {

    public static function init() : void {
		self::registerEntities();
	}

	private static function registerEntities() : void {
		foreach (self::getEntities() as $name => $class) {
			$nametolower = strtolower($name);
			CustomiesItemFactory::getInstance()->registerItem(fn() => new Egg(ItemTypeIds::newId(), $nametolower), "vanillamobs:egg_" . $nametolower, new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_NATURE));
			EntityFactory::getInstance()->register($class, function (World $world, CompoundTag $nbt) use ($class) : Entity {
				return new $class(EntityDataHelper::parseLocation($nbt, $world), $nbt);
			}, [$name]);

			Server::getInstance()->getLogger()->info("Registered entity: " . $name . " with class: " . $class);
		}
	}

	private static function getEntities() : array {
		return [
			"Zombie" => Zombie::class,
			"Sheep" => Sheep::class,
			"Pig" => Pig::class
		];
	}
	public static function getEntity(string $mob, mixed...$args) : ?Entity {
		$entities = self::getEntities();
		$mob = ucwords($mob);

		$entity = null;
		if (isset($entities[$mob])) {
			try {
				$entity = new $entities[$mob](...$args);
			} catch (\Exception $e) {
				Server::getInstance()->getLogger()->error("Error while creating entity: " . $e->getMessage());
			}
		}

		return $entity;
	}

    public static function summon(string $entity, ...$args) : bool {
		$entity = self::getEntity($entity, ...$args);

		if ($entity !== null) {
			$entity->spawnToAll();
			return true;
		}

		return false;
	}
}