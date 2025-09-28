<?php

declare(strict_types=1);

namespace ItIsDev\VanillaMobs\entity\trait;

use pocketmine\item\VanillaItems;

trait WearArmor {

    protected bool|int $armorExisted = false;

    public function wearable(): bool {
        return true;
    }

    public function randomArmor(): void {
        $inv = $this->getArmorInventory();
        
        $helmet = mt_rand(1, 100);
        $chestplate = mt_rand(1, 100);
        $leggings = mt_rand(1, 100);
        $boots = mt_rand(1, 100);
        
        if($helmet <= 5){
            $roll = mt_rand(1, 100);
            if($roll <= 60){
                $inv->setHelmet(VanillaItems::LEATHER_CAP());
            }elseif($roll <= 80){
                $inv->setHelmet(VanillaItems::GOLDEN_HELMET());
            }elseif($roll <= 95){
                $inv->setHelmet(VanillaItems::CHAINMAIL_HELMET());
            }elseif($roll <= 99){
                $inv->setHelmet(VanillaItems::IRON_HELMET());
            }else{
                $inv->setHelmet(VanillaItems::DIAMOND_HELMET());
            }
        }
        
        if($chestplate <= 5){
            $roll = mt_rand(1, 100);
            if($roll <= 60){
                $inv->setChestplate(VanillaItems::LEATHER_TUNIC());
            }elseif($roll <= 80){
                $inv->setChestplate(VanillaItems::GOLDEN_CHESTPLATE());
            }elseif($roll <= 95){
                $inv->setChestplate(VanillaItems::CHAINMAIL_CHESTPLATE());
            }elseif($roll <= 99){
                $inv->setChestplate(VanillaItems::IRON_CHESTPLATE());
            }else{
                $inv->setChestplate(VanillaItems::DIAMOND_CHESTPLATE());
            }
        }
        
        if($leggings <= 5){
            $roll = mt_rand(1, 100);
            if($roll <= 60){
                $inv->setLeggings(VanillaItems::LEATHER_PANTS());
            }elseif($roll <= 80){
                $inv->setLeggings(VanillaItems::GOLDEN_LEGGINGS());
            }elseif($roll <= 95){
                $inv->setLeggings(VanillaItems::CHAINMAIL_LEGGINGS());
            }elseif($roll <= 99){
                $inv->setLeggings(VanillaItems::IRON_LEGGINGS());
            }else{
                $inv->setLeggings(VanillaItems::DIAMOND_LEGGINGS());
            }
        }
        
        if($boots <= 5){
            $roll = mt_rand(1, 100);
            if($roll <= 60){
                $inv->setBoots(VanillaItems::LEATHER_BOOTS());
            }elseif($roll <= 80){
                $inv->setBoots(VanillaItems::GOLDEN_BOOTS());
            }elseif($roll <= 95){
                $inv->setBoots(VanillaItems::CHAINMAIL_BOOTS());
            }elseif($roll <= 99){
                $inv->setBoots(VanillaItems::IRON_BOOTS());
            }else{
                $inv->setBoots(VanillaItems::DIAMOND_BOOTS());
            }
        }
        $this->armorExisted = true;
    }
}