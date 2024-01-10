<?php

namespace App\Entity;
class Coin
{
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