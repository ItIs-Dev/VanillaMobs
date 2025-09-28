<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs;

use ItIsDev\VanillaMobs\entity\Egg;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener {

    public function onInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if($item instanceof Egg){
            $block = $event->getBlock();
            $pos = $block->getPosition()->add(0, 1, 0);
            
            $item->spawnEntity($player, $pos);

            $item = $item->setCount($item->getCount() - 1);
            $player->getInventory()->setItemInHand($item);
            $event->cancel();
        }
    }
}