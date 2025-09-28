<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs;

use ItIsDev\VanillaMobs\commands\VanillaMobsCommand;
use ItIsDev\VanillaMobs\entity\MobsManager;
use pocketmine\plugin\PluginBase;

class VanillaMobs extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getLogger()->info("VanillaMobs plugin has been enabled!");
        $this->getServer()->getCommandMap()->register("vanillamobs", new VanillaMobsCommand());
        MobsManager::init();
    }

    public function onDisable(): void {
        $this->getLogger()->info("VanillaMobs plugin has been disabled!");
    }
}