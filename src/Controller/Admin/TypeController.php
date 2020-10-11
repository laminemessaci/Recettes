<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class TypeController extends AbstractController
{
    /**
     * @Route("/admin/type", name="admin_types")
     */
    public function index(TypeRepository $repository)
    {
        $types = $repository->findAll();
        return $this->render('admin/type/adminType.html.twig', [
            'types' => $types
        ]);
    }

    /**
     * @Route("/admin/type/create", name="ajoutTypes")
     * @Route("/admin/type/{id}", name="modifTypes", methods="POST|GET")
     */
    public function ajoutEtMofif(Type $type = null, Request $request)
    {

        //boolean pour savoir si modif ou suppression
        $modif = $type->getId() != null;

       if (!$type){
           $type = new Type();
       }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
        $manager->persist($type);
        $manager->flush();
            //ajout de flash message
            $this->addFlash("success", ($modif)? "La modification a été effectuée": "L'ajout a été effectué ");
       return $this->redirectToRoute("admin_types");

    }
        return $this->render('admin/type/ajoutEtModif.html.twig', [
            "type" => $type,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/type/{id}", name="supType", methods="DELETE")
     */
    public function suppression(Type $type, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('SUP'.$type->getId(), $request->get('_token'))){
            $manager->remove($type);
            $manager->flush();
            //ajout de flash message
            $this->addFlash("success",  "Le type a été supprimé ");
            return $this->redirectToRoute("admin_types");
        }

    }
}
