<?php

namespace App\Controller;

use App\Entity\ListType;
use App\Form\ListTypeType;
use App\Repository\ListTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/list/type')]
class ListTypeController extends AbstractController
{
    #[Route('/', name: 'app_list_type_index', methods: ['GET'])]
    public function index(ListTypeRepository $listTypeRepository): Response
    {
        $user = $this->getUser();
        if ($user && $user->getRoles()[0] == 'ROLE_USER') {
            return $this->render('list_type/index.html.twig', [
                'list_types' => $listTypeRepository->findByExampleField($user->getId()),
            ]);
        }
        return $this->render('list_type/index.html.twig', [
            'list_types' => $listTypeRepository->findAll(),
        ]);
    }



    #[Route('/{id}', name: 'app_list_type_show', methods: ['GET'])]
    public function show(ListType $listType, ListTypeRepository $listTypeRepository, $id): Response
    {
        $user = $this->getUser();
        $list = $listTypeRepository->findByIdField($id);
        if (($list  && $list[0]->getUserId() == $user->getId()) || $user->getRoles()[0] == 'ROLE_ADMIN'  ) {
        return $this->render('list_type/show.html.twig', [
            'list_type' => $listType,
        ]); }
        return $this->redirectToRoute('app_list_type_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_list_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListType $listType, EntityManagerInterface $entityManager, ListTypeRepository $listTypeRepository, $id): Response
    {
        $user = $this->getUser();
        $list = $listTypeRepository->findByIdField($id);
        if (($list  && $list[0]->getUserId() == $user->getId()) || $user->getRoles()[0] == 'ROLE_ADMIN'  ) {
        $form = $this->createForm(ListTypeType::class, $listType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_list_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('list_type/edit.html.twig', [
            'list_type' => $listType,
            'form' => $form,
        ]); }
        return $this->redirectToRoute('app_list_type_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}', name: 'app_list_type_delete', methods: ['POST'])]
    public function delete(Request $request, ListType $listType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($listType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_list_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
