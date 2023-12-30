<?php

namespace App\Controller;

use App\Entity\Mj;
use App\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\UX\Turbo\TurboBundle;

class MjController extends AbstractController
{
    protected Mj $mj_object;

    public function __construct(){
        $this->mj_object = new Mj();
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $randomObjects = $this->mj_object->getRandomObjects();
        $result = new Result();

        // Create the form
        $formB = $this->createFormBuilder($result)
            ->setAction($this->generateUrl('results'))
            ->setMethod('POST')
            ->add('difficulty', RangeType::class, [
                'label' => 'Difficulty',
                'attr' => [
                    'class' => 'difficulty',
                    'min' => 0,
                    'max' => 100,
                    'oninput' => 'updateTextInput(this.value)',
                ],
            ]);
        $formB->add('clicked_button', HiddenType::class, ['mapped' => false]);
        foreach ($randomObjects as $object) {
            $text = $object->object == 'Coin' ? $object->object : ($object->object == 'Deck' ? $object->object . $object->max : "D" . $object->max);
            $formB->add('button_' . $object->getId(), SubmitType::class, ['label' => $text, 'attr' => [ 'class' => $object->object]]);
        }



        $form = $formB->getForm();
        $rollResult = null;


    $response = $this->render('mj/index.html.twig', [
    'controller_name' => 'MjController',
    'randomObjects' => $randomObjects,
    'form' => $form,
    'rollResult' => $rollResult,
]);

        return $response;

    }
    #[Route('/results', name: 'results')]
    public function results(Request $request): Response
    {
        $randomObjects = $this->mj_object->getRandomObjects();
        $result = new Result();

        // Create the form
        $form = $this->createFormBuilder($result)
            ->setAction($this->generateUrl('results'))
            ->setMethod('POST')
            ->add('difficulty', RangeType::class, [
                'label' => 'Difficulty',
                'attr' => [
                    'class' => 'difficulty',
                    'min' => 0,
                    'max' => 100,
                    'oninput' => 'updateTextInput(this.value)',
                ],
            ]);

        foreach ($randomObjects as $object) {
            $text = $object->object == 'Coin' ? $object->object : ($object->object == 'Deck' ? $object->object . $object->max : "D" . $object->max);
            $form->add('button_' . $object->getId(), SubmitType::class, ['label' => $text, 'attr' => [ 'class' => $text]]);
        }

        $form = $form->getForm();
        $form->handleRequest($request);

        $rollResult = null;
        if ($request->isMethod('POST')) {


            if ($form->isSubmitted() && $form->isValid()) {
                // Retrieve the clicked button value
                //$formData = $request->request->all();

                // Retrieve the clicked button value
                $clickedButton = null;
                foreach ($randomObjects as $object) {
                    $buttonName = 'button_' . $object->getId();
                    if ($form->get($buttonName)->isClicked()) {
                        if($object->object == 'Coin')
                        {
                            $clickedButton = $object->object;
                        }
                        else{
                            $clickedButton = $object->object.$object->max;
                        }
                        break;
                    }
                }

                if ($clickedButton !== null) {
                    // Redirect or perform other actions
                    //var_dump($clickedButtonValue);
                    $crtRate = $form->get('difficulty')->getData();
                    $choice = $clickedButton;
                    $results = $this->mj_object->rollForCrtByObject($crtRate, $choice);
                    //$request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                    $response = $this->render('mj/results.html.twig', [
                        'controller_name' => 'MjController',
                        'rollResult' => $results[0],
                        'objectChoice' => $results[1],
                        'maxValue' => $results[2],
                        'value' => $results[3],
                        'crtRate' => $crtRate,
                        'randomObjects' => $randomObjects,
                        'form' => $form->createView(),
                    ]);
                    $response->setStatusCode(303);
                    return $response;
                }
            }
        }
        $response = $this->render('mj/index.html.twig', [
            'controller_name' => 'MjController',
            'randomObjects' => $randomObjects,
            'rollResult' => null,
            'form' => $form->createView(),
        ]);
        $response->setStatusCode(303);
        return $response;
    }
}
