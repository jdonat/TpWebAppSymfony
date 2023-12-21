<?php

namespace App\Controller;

use App\Entity\Mj;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class MjController extends AbstractController
{
    protected Mj $mj_object;

    public function __construct(){
        $this->mj_object = new Mj();
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $crtRate = mt_rand(20, 80)/100;
        $Results = $this->mj_object->rollForCrt($crtRate);
        $rollResult = $Results[0];
        $objectChoice = $Results[1];
        $maxValue = $Results[2];
        $value = $Results[3];
        return $this->render('mj/index.html.twig', [
            'controller_name' => 'MjController',
            'rollResult' => $rollResult,
            'objectChoice' => $objectChoice,
            'maxValue' => $maxValue,
            'value' => $value,
            'crtRate' => $crtRate*100,
        ]);
    }
}
