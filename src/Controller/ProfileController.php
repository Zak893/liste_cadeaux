<?php

// src/Controller/ProfileController.php

namespace App\Controller;

use App\Form\Userupdate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Créez un formulaire de modification des informations utilisateur
        $form = $this->createForm(Userupdate::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez les modifications dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();


            // Redirigez l'utilisateur vers la page de profil (ou une autre page)
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
