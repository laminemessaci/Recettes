<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/aliment", name="admin_aliment")
     */
    public function index(AlimentRepository $repository)
    {
        $aliments = $repository->findAll();
        return $this->render('admin/adminAliment.html.twig', [
            "aliments" => $aliments
        ]);
    }

    /**
     * @Route("/admin/aliment/creation", name="admin_aliment_creation")
     * @Route("/admin/aliment/{id}", name="admin_aliment_modification", methods="GET|POST")
     */
    public function ajoutModification(Aliment $aliment = null, Request $request)
    {
        if (!$aliment) {$aliment = new Aliment();}

        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AlimentType::class, $aliment);
        //boolean pour savoir si modif ou suppression
        $modif = $aliment->getId() != null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($aliment);
            $manager->flush();
            //ajout de flash message
            $this->addFlash("success", ($modif)? "La modification a été effectuée": "L'ajout a été effectué ");
            return $this->redirectToRoute('admin_aliment');
        }

        return $this->render('admin/modifEtAjoutAliment.html.twig', [
            "aliment" => $aliment,
            "form" => $form->createView(),
            "isModification" => $aliment->getId() !== null // cette condition est valable si le champ id existe en BDD
        ]);

    }

    /**
     * @Route("/admin/aliment/{id}", name="admin_aliment_suppression", methods="DELETE")
     */
    public function suprimerAliment(Aliment $aliment, Request $request)
    {

        if ($this->isCsrfTokenValid('SUP' . $aliment->getId(), $request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($aliment);
            $manager->flush();
            //ajout de flash message
            $this->addFlash("success", "La suppression a été effectuée");
            return $this->redirectToRoute("admin_aliment");
        }
    }

}
