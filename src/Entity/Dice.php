<?php
namespace App\Entity;
class Dice
{
    protected int $nbFace;
    public function __construct($face){
        $this->nbFace = $face;
    }
    public function roll() : int {
        return mt_rand(1, $this->nbFace);
    }
    public function getRandomInt() : int {
        return $this->roll();
    }
    public function getNbFace() : int {
        return $this->nbFace;
    }
}