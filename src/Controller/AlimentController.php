<?php

namespace App\Controller;

use App\Repository\AlimentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AlimentController extends AbstractController
{
    /**
     * @Route("/", name="aliments")
     */
    public function index(AlimentRepository  $repository)
    {
        $aliments = $repository->findAll();
        return $this->render('aliment/aliments.html.twig', [
            'aliments' => $aliments,
            'isCalorie' => false
        ]);
    }

    /**
     * @Route("/aliments/{calorie}", name="alimentsParCalorie")
     * @param AlimentRepository $repository
     * @param $calorie
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function alimentMoinsCaloriques (AlimentRepository $repository, $calorie)
    {
        $aliments = $repository->getAlimentParCalories('calorie', '<' ,$calorie);
        return $this->render('aliment/aliments.html.twig', [
            'aliments' => $aliments,
            'isCalorie' =>true
        ]);
    }

}
