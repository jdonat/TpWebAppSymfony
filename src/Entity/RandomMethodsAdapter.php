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
        $index=0;

        try {
            if ($coinList != null) {
                for ($i = 0; $i < count($coinList); $i++) {
                    $this->coins[] = new Coin();
                    $this->coins[$index]->setId($index);
                    $index++;
                }
            } else {
                throw new Error("coin list is empty");
            }
            if ($deckList != null) {
                for ($i = 0; $i < count($deckList); $i++) {
                    $this->decks[] = new Deck($deckList[$i]);
                    echo $index;
                    $this->decks[$i]->setId($index);
                    $index++;
                }
            } else {
                throw new Error("deck list is empty");
            }
            if ($diceList != null) {
                for ($i = 0; $i < count($diceList); $i++) {
                    if (is_int($diceList[$i])) {
                        if ($diceList[$i] > 0) {
                            $this->dices[] = new Dice($diceList[$i]);
                            $this->dices[$i]->setId($index);
                            $index++;
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


        } catch (Error $e) {
            echo $e;
        }

    }

    public function generateRandomMethod($threshold): array
    {
        $choice = mt_rand(0, 2);
        $coinIndex = mt_rand(0, count($this->coins) - 1);
        $deckIndex = mt_rand(0, count($this->decks) - 1);
        $diceIndex = mt_rand(0, count($this->dices) - 1);
        switch ($choice) {
            case 0 :
                //echo "nombre de lancer : ".sprintf("%.3f", floor($threshold*10));
                $value = $this->coins[$coinIndex]->getRandomInt(floor($threshold * 10));
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
        $rollResult = ($value >= $threshold * $maxValue);
        return [$rollResult, $objectChoice, $maxValue, $value];
    }

    public function getRandomObjects(): array
    {
        $objects[] = $this->coins[0];
        foreach ($this->decks as $d) {
            $objects[] = $d;
        }
        foreach ($this->dices as $d) {
            $objects[] = $d;
        }
        return $objects;
    }

    public function generateMethodByObject($threshold, $objectId): array
    {
        $value = -1;
        $maxValue = -1;
        $rollResult = -1;
        $objectChoice = '';
        if ($objectId == 'Coin') {
            //echo "nombre de lancer : ".sprintf("%.3f", floor($threshold*10));
            $value = $this->coins[0]->getRandomInt(floor($threshold / 10));
            $maxValue = 1;
            $objectChoice = "Coin";
        }
        else{
            if (str_contains($objectId, 'Deck')) {
                $objectId = trim($objectId,"Deck");
                $max = (int) $objectId;
                foreach($this->decks as $d)
                {
                    if($d->getNbCard() == $max)
                    {
                        $value = $d->getRandomInt();
                        $maxValue = $max;
                        break 1;
                    }
                }
                $objectChoice = "Deck";
            } else {
                if (str_contains($objectId, 'Dice')) {
                    $objectId = trim($objectId,"Dice");
                    $max = (int) $objectId;
                    $i=-1;
                    foreach($this->dices as $d)
                    {
                        $i++;
                        if($d->getNbFace() == $max)
                        {
                            $value = $d->getRandomInt();
                            $maxValue = $d->getNbFace();
                            break 1;
                        }
                        $maxValue = $d->getNbFace();
                    }
                    $objectChoice = "Dice";
                }
            }
        }

        if ($objectChoice == 'Coin') {
            $rollResult = $value;
        }
        else{
            $rollResult = ($value >= ($threshold / 100) * $maxValue);
        }


        return [$rollResult, $objectChoice, $maxValue, $value];
    }
}