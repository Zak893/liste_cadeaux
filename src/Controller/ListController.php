<?php
// src/Controller/ListController.php

namespace App\Controller;

use App\Entity\ListType; // Remplacez ListEntity par le nom de votre entité de liste
use App\Form\Listform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ListController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function createList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $list = new ListType(); // Remplacez ListEntity par le nom de votre entité de liste

        $form = $this->createForm(Listform::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez les données du formulaire ici, par exemple, enregistrez la liste en base de données.
            $entityManager->persist($list);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une page de confirmation ou ailleurs.
            return $this->redirectToRoute('list');
        }

        return $this->render('list/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
