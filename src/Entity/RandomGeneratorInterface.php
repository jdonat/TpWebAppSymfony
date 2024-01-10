<?php

namespace App\Entity;

interface RandomGeneratorInterface
{
    public function getRandomInt() : int;

}