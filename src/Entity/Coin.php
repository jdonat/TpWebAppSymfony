<?php

namespace App\Entity;
class Coin
{
    public string $object, $max;
    protected int $id;


    public function __construct()
    {
        $this->object = "Coin";
        $this->id = -1;
        $this->max = 1;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function toss() : bool {
        return (bool) mt_rand(0, 1);
    }
    public function tossInInt($res, $ite) : int {
        //echo "\n".$res." ".$ite."   /";
        if($ite <= 0)
        {
            return $res;
        }
        else{
            if($res>0)
            {
                //echo $res." ".$ite."WIN   //---//   ";
                $res=mt_rand(0, 1);
                $ite--;
                return $this->tossInInt($res, $ite);
            }
            else{
                return 0;
            }
        }
    }
    public function getRandomInt($nb) : int {
        //$r =;
        //echo " res=".$r." ";
        return $this->tossInInt(1, $nb);
    }
}