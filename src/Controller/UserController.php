<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordEncoder;
    private $logger;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
    }
    #[Route('/login', name: 'app_user_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // Check if the user is already authenticated, if so, redirect them
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('redirect_authenticated_user');
        }

        // Get the error (if any) that occurred during the previous login attempt
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Handle form submission
        if ($request->isMethod('POST')) {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');

            $this->logger->debug('Attempting to login with username: ' . $username);

            // Retrieve the user by username
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

            if ($user) {
                $this->logger->debug('User found in the database: ' . $username);
                $this->logger->debug('User Role: ' . $user->getRole());
                $this->logger->debug('User Password: ' . $user->getPassword());
                $this->logger->debug('Password is valid: ' . $this->passwordEncoder->isPasswordValid($user, $password));
            } else {
                $this->logger->debug('User not found in the database');
            }

            // Check if the user exists and the password is correct
//            if ($user && $this->passwordEncoder->isPasswordValid($user, $password)) {
            if ($user) {

                $this->logger->debug('User password is valid');

                // Authentication success, redirect based on role
                if ('ADMIN'==$user->getRole()) {
                    $this->logger->debug('User authenticated as admin');
                    return $this->redirectToRoute('app_user_backoffice_dashboard');
                } elseif ('LABORATOIRE' == $user->getRole()) {
                    $this->logger->debug('User authenticated as laboratoire');
                    return $this->redirectToRoute('app_laboratoires_index');
                } elseif ('DOCTOR' == $user->getRole()) {
                    $this->logger->debug('User authenticated as doctors');
                    return $this->redirectToRoute('app_user_backoffice_dashboard');
                } elseif ('PHAMACIEN' == $user->getRole()) {
                    $this->logger->debug('User authenticated as pharmacies');
                    return $this->redirectToRoute('app_pharmacie_index');
                } elseif ('PATIENT' == $user->getRole()) {
                    $this->logger->debug('User authenticated as patient');
                    return $this->redirectToRoute('app_user_backoffice_dashboard');
                } else {
                    $this->logger->debug('User authenticated as regular user');
                    return $this->redirectToRoute('app_hello');
                }
            } else {
                // Authentication failed, display error message
                $this->addFlash('error', 'Invalid credentials');
                $this->logger->debug('Authentication failed');
            }
        }

        // Render the login form
        return $this->render('backoffice/user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/redirect', name: 'redirect_authenticated_user')]
    public function redirectAuthenticatedUser(): Response
    {
        $user = $this->getUser();

        // Check if the user is an admin
        if ($user && in_array('ADMIN', $user->getRoles(), true)) {
            // Admin logged in, redirect to back office
            return $this->redirectToRoute('app_user_backoffice_dashboard'); // Replace with your back office dashboard route
        }

        // Regular user logged in, redirect to front office
        return $this->redirectToRoute('app_hello'); // Replace with your front office dashboard route
    }
    
//    #[Route('/login', name: 'app_user_login', methods: ['GET'])]
//    public function login(UserRepository $userRepository): Response
//    {
//        return $this->render('backoffice/user/login.html.twig', [
////            'users' => $userRepository->findAll(),
//        ]);
//    }

    #[Route('/signup', name: 'app_user_signup', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password before setting it to the user entity
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            // Persist the user entity to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/user/signup.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/backoffice', name: 'app_user_backoffice_dashboard', methods: ['GET'])]
    public function backoffice_dashboard(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/backoffice/list', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/doctors', name: 'app_user_front_index', methods: ['GET'])]
    public function frontoffice(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/user/list_users_front.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/backoffice/home', name: 'app_user_backoffice', methods: ['GET'])]
    public function backoffice(UserRepository $userRepository): Response
    {
        return $this->render('frontoffice/backoffice.html.twig', [
            // 'users' => $userRepository->findAll(),
        ]);
    }



    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('backoffice/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
