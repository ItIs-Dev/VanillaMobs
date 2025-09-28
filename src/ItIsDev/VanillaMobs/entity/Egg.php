<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity;

use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\block\Block;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class Egg extends Item implements ItemComponents {

    use ItemComponentsTrait;

    public function __construct(int $id, string $entityName) {
        parent::__construct(new ItemIdentifier($id), ucfirst($entityName) . " Spawn Egg");
        $this->initComponent("spawn_egg_" . $entityName);
        $this->getNamedTag()->setString("name_entity", ucfirst($entityName));
    }

    public function getMaxStackSize(): int {
        return 64;
    }

    public function getEntityName(): string {
        return $this->getNamedTag()->getString("name_entity");
    }

    public function spawnEntity(Player $player, Vector3 $vector): void {
        $pl = $player->getLocation();
        MobsManager::summon($this->getEntityName(), new Location($vector->x, $vector->y, $vector->z, $player->getWorld(), $pl->yaw, $pl->pitch));
    }
}