<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\commands;

use ItIsDev\VanillaMobs\entity\MobsManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class VanillaMobsCommand extends Command {

    public function __construct() {
        parent::__construct("vanillamobs", "VanillaMobs command", "/vanillamobs summon <mob>", ["mobs"]);
        $this->setPermission("vanillamobs.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        if (!$sender instanceof Player) return false;

        if (count($args) === 0) {
            $sender->sendMessage("Usage: /vanillamobs summon <mob>");
            return true;
        }

        switch ($args[0]) {
            case "summon":
                if (count($args) < 2) {
                    $sender->sendMessage("Usage: /vanillamobs summon <mob>");
                    return true;
                }

                $mob = $args[1];
                $location = $sender->getLocation();

                if (MobsManager::summon($mob, $location)) {
                    $sender->sendMessage("Summoned " . $mob . " at your position.");
                } else {
                    $sender->sendMessage("Failed to summon " . $mob . ". Make sure the mob name is correct.");
                }
                break;

            default:
                $sender->sendMessage("Unknown subcommand. Usage: /vanillamobs summon <mob>");
                break;
        }

        return true;
    }
}