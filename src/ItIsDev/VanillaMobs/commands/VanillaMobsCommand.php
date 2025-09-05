<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\commands;

use ItIsDev\VanillaMobs\entity\MobsManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class VanillaMobsCommand extends Command {

    public function __construct() {
        parent::__construct("summon", "VanillaMobs command", "/summon <mob>");
        $this->setPermission("vanillamobs.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        if (!$sender instanceof Player) return false;

        if (count($args) < 1) {
            $sender->sendMessage("Usage: /summon <mob>");
            return true;
        }

        $mob = $mob = implode(" ", $args);

        $location = $sender->getLocation();

        if (MobsManager::summon($mob, $location)) {
            $sender->sendMessage("Summoned " . $mob . " at your position.");
        } else {
            $sender->sendMessage("Failed to summon " . $mob . ". Make sure the mob name is correct.");
        }

        return true;
    }
}