<?php

// src/Controller/AdminUserController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/backoffice', name: 'app_utilisateur_backoffice_dashboard', methods: ['GET'])]
    public function backofficeDashboard(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/backoffice/list', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }
    #[Route('/backoffice/user/{id}/ban', name: 'admin_user_ban', methods: ['POST'])]
    public function banUser(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $utilisateur->setBanned(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_utilisateur_index');
    }

    #[Route('/backoffice/user/{id}/unban', name: 'admin_user_unban', methods: ['POST'])]
    public function unbanUser(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $utilisateur->setBanned(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_utilisateur_index');
    }

    // Other admin-related actions can be added here...

}
