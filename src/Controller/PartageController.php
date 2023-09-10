<?php

namespace App\Controller;

use App\Repository\GiftRepository;
use App\Repository\ListTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartageController extends AbstractController
{
    #[Route('/partage/{id}', name: 'app_partage')]
    public function index(ListTypeRepository $listTypeRepository, $id, GiftRepository $giftRepository): Response
    {
        $listType = $listTypeRepository->findByIdField($id)[0];
        $gifts =$giftRepository->findByListIdField($listType->getId());

        return $this->render('list_type/partage.html.twig', [
            'list_type' => $listTypeRepository->findByIdField($id)[0],
            'gifts' => $gifts,
        ]);
    }

    #[Route('/partage_show/{id}', name: 'app_partage_show')]
    public function show(ListTypeRepository $listTypeRepository,Request $request, $id, GiftRepository $giftRepository): Response
    {
        $listType = $listTypeRepository->findByIdField($id)[0];

        $isPasswordVerified = false;

        // Vérifiez si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérez le mot de passe soumis par l'utilisateur
            $submittedPassword = $request->request->get('password');
            $dateOuvert = new \DateTime($listTypeRepository->findByIdField($id)[0]->getDateOuvert());
            $dateFin = new \DateTime($listTypeRepository->findByIdField($id)[0]->getDateFin());
            $dateActuelle = new \DateTime();

            $gifts =$giftRepository->findByListIdField($listType->getId());

            // Vérifiez si le mot de passe soumis correspond au mot de passe stocké
            if ($submittedPassword === $listType->getPassword() && ($dateActuelle >= $dateOuvert && $dateActuelle <= $dateFin)) {
                $isPasswordVerified = true;
                return $this->render('list_type/partageShow.html.twig', [
                    'list_type' => $listType,
                    'is_password_verified' => $isPasswordVerified,
                    'gifts' => $gifts,
                ]);
            }
        }

        return $this->render('list_type/partage.html.twig', [
            'list_type' => $listTypeRepository->findByIdField($id)[0],
        ]);
    }
}
