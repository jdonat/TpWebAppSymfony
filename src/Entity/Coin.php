<?php

namespace App\Entity;
class Coin
{
    public function toss() : bool {
        return (bool) mt_rand(0, 1);
    }
    public function tossInInt() : int {
        return mt_rand(0, 1);
    }
    public function getRandomInt() : int {
        return $this->tossInInt();
    }
}