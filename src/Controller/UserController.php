<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UtilisateurRepository $userRepository): Response
    {
        return $this->render('frontoffice/user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/backoffice', name: 'app_utilisateur_backoffice_dashboard', methods: ['GET'])]
    public function backoffice_dashboard(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/backoffice/list', name: 'app_utilisateur_index', methods: ['GET'])]
    public function indexx(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/doctors', name: 'app_utilisateur_front_index', methods: ['GET'])]
    public function frontoffice(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('backoffice/user/list_users_front.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/backoffice/home', name: 'app_utilisateur_backoffice', methods: ['GET'])]
    public function backoffice(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('frontoffice/backoffice.html.twig', [
            // 'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('backoffice/user/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/user/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
