<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


class SecurityController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        try {
            $user = $this->getUser();
            
            // Check if user is banned
            if ($user && $user->isBanned()) {
                $this->addFlash('error', 'Your account has been banned.');
                return $this->redirectToRoute('app_logout');
            }

            if ($user && $user->getRole() === 'PATIENT') {
                return $this->redirectToRoute('app_hello');
            } elseif ($user && $user->getRole() === 'ADMIN') {
                return $this->redirectToRoute('app_utilisateur_backoffice_dashboard');
            }

            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        } catch (CustomUserMessageAuthenticationException $e) {
            // Handle custom authentication exception
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_login'); // Redirect to login page
        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_hello');
    }
}