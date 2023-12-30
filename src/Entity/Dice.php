<?php
namespace App\Entity;
class Dice
{
    protected int $nbFace;
    public string $max, $object;
    protected int $id;


    public function __construct($face){
        $this->nbFace = $face;
        $this->max = strval($face);
        $this->object = "Dice";
        $this->id = -1;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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