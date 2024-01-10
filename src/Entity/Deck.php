<?php

namespace App\Entity;

use Error;

class Deck
{
    protected array $color = ['spades', 'diamonds', 'hearts', 'clubs'];
    protected int $nbCard = 0;
    protected int $cardNbValue = 0;
    protected int $colorNbValue = 0;

    public function __construct($nbCards)
    {
        try {
            if ($nbCards == 36 || $nbCards == 52) {
                $this->colorNbValue = count($this->color);
                $this->cardNbValue = $nbCards / $this->colorNbValue;
                $this->nbCard = $nbCards;
            } else {
                throw new Error("nbCards value is not 36 or 52");
            }
        } catch (Error $e) {
            echo $e;
        }
    }
    public function getNbCard() : int
    {
        return $this->nbCard;
    }
    public function getCardByInt($id) : array
    {
        $color = floor($id / $this->cardNbValue);

        $value = $id - ($this->colorNbValue*$this->cardNbValue);
        return [$color, $value];
    }
    public function draw(): array
    {
        $this->cardNbValue = mt_rand(1, $this->cardNbValue);
        $this->colorNbValue = mt_rand(0, count($this->color)-1);
        return [$this->colorNbValue, $this->cardNbValue];
    }
    public function drawInInt(): int
    {
        $this->cardNbValue = mt_rand(1, $this->cardNbValue);
        $this->colorNbValue = mt_rand(0, count($this->color)-1);
        return $this->cardNbValue * ($this->colorNbValue+1);
    }
    public function getRandomInt(): int
    {
        return $this->drawInInt();
    }
}