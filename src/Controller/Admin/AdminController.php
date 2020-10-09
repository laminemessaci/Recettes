<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/aliment", name="admin_aliment")
     */
    public function index( AlimentRepository  $repository)
    {
        $aliments = $repository -> findAll();
        return $this->render('admin/adminAliment.html.twig',[
            "aliments" => $aliments
            ]);
    }

    /**
     * @Route("/admin/aliment/creation", name="admin_aliment_creation")
     * @Route("/admin/aliment/{id}", name="admin_aliment_modification")
     */
    public function ajoutModification(Aliment $aliment = null, Request $request)
    {
        if (!$aliment){
            $aliment = new Aliment();
        }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AlimentType::class, $aliment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
          $manager->persist($aliment);
          $manager->flush();
          return  $this->redirectToRoute('admin_aliment');
        }

        return $this->render('admin/modificationAliment.html.twig',[
            "aliment" =>$aliment,
            "form" => $form->createView(),
            "isModification" => $aliment->getId() !== null // cette condition est valable si le champ id existe en BDD
        ]);
    }
}
