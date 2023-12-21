<?php

namespace App\Entity;

use Error;

class RandomMethodsAdapter
{
    protected array $coins;
    protected array $decks;
    protected array $dices;
    //D4, D6, D8, D10, D12, D20, D100

    public function __construct($coinList, $deckList, $diceList)
    {
        try {
            if ($diceList != null) {
                for ($i = 0; $i < count($diceList); $i++) {
                    if (is_int($diceList[$i])) {
                        if ($diceList[$i] > 0) {
                            $this->dices[] = new Dice($diceList[$i]);
                        } else {
                            throw new Error("entry #$i in dices face list is negative");
                        }
                    } else {
                        throw new Error("entry #$i in dices face list is not a number");
                    }
                }
            } else {
                throw new Error("dices face list is empty");
            }
            if ($coinList != null) {
                for ($i = 0; $i < count($coinList); $i++) {
                    $this->coins[] = new Coin();
                }
            } else {
                throw new Error("coin list is empty");
            }
            if ($deckList != null) {
                for ($i = 0; $i < count($deckList); $i++) {
                    $this->decks[] = new Deck($deckList[$i]);
                }
            } else {
                throw new Error("deck list is empty");
            }
        } catch (Error $e) {
            echo $e;
        }

    }
    public function generateRandomMethod($threshold): array
    {
            $choice = mt_rand(0,  2);
            $coinIndex = mt_rand(0,count($this->coins)-1);
            $deckIndex = mt_rand(0,count($this->decks)-1);
            $diceIndex = mt_rand(0,count($this->dices)-1);
            switch ($choice) {
                case 0 :
                    //echo "nombre de lancer : ".sprintf("%.3f", floor($threshold*10));
                    $value = $this->coins[$coinIndex]->getRandomInt(floor($threshold*10));
                    $maxValue = 1;
                    $objectChoice = "Coin";
                    break;
                case 1 :
                     $value = $this->decks[$deckIndex]->getRandomInt();
                     $maxValue = $this->decks[$deckIndex]->getNbCard();
                     $objectChoice = "Deck";
                    break;
                default :
                    $value = $this->dices[$diceIndex]->getRandomInt();
                    $maxValue = $this->dices[$diceIndex]->getNbFace();
                    $objectChoice = "Dice";
                    break;
            }
        $rollResult = ( $value >= $threshold*$maxValue);
        return [$rollResult, $objectChoice, $maxValue, $value];
    }
}