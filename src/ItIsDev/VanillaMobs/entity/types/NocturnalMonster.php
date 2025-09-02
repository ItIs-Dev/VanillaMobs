<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity\types;

use ItIsDev\VanillaMobs\entity\Monster;
use pocketmine\event\entity\EntityDamageEvent;

class NocturnalMonster extends Monster {

    public function onDay(): bool {
        $world = $this->getWorld();

        $time = $world->getTimeOfDay();

        if($time >= 12542) return false;

        return true;
    }

    public function entityBaseTick(int $tickDiff = 1): bool {
        $up = parent::entityBaseTick();

        if($this->onDay()) $this->setOnFire(5);

        if($this->isOnFire()) {
            $this->attack(new EntityDamageEvent($this, EntityDamageEvent::CAUSE_FIRE, 1));
        }

        return $up;
    }
}