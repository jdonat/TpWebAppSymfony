<?php

namespace App\Entity;
class Mj
{
    protected RandomMethodsAdapter $chance;
    public function __construct(){
        $this->chance = new RandomMethodsAdapter([1], [52, 36], [6, 12]);
    }
    public function rollForCrt($crtRate) : array {
        return $this->chance->generateRandomMethod($crtRate);
    }
    public function rollForCrtByObject($crtRate, $object) : array{
        return $this->chance->generateMethodByObject($crtRate, $object);
    }
    public function getRandomObjects() : array{
        return $this->chance->getRandomObjects();
    }
}