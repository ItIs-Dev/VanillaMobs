<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity\trait;

use pocketmine\event\entity\EntityDamageEvent;

trait Nocturnal {

    public function onDay(): bool {
        $groundY = $this->getWorld()->getHighestBlockAt((int)$this->getPosition()->getX(), (int)$this->getPosition()->getZ());
        if($this->getPosition()->getY() < $groundY) return false;

        $world = $this->getWorld();

        $time = $world->getTimeOfDay();

        if($time >= 12542) {
            return false;
        }

        return true;
    }

    public function fireOnDay(int $second, float $damage): void {
        if($this->onDay()) $this->setOnFire($second);

        if($this->isOnFire()) {
            $this->attack(new EntityDamageEvent($this, EntityDamageEvent::CAUSE_FIRE, $damage));
        }
    }
}