<?php

namespace App\Controller;
use App\Form\RegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ResetPasswordRequest;

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
//    #[Route('/backoffice', name: 'app_utilisateur_backoffice_dashboard', methods: ['GET'])]
//    public function backoffice_dashboard(UtilisateurRepository $utilisateurRepository): Response
//    {
//
//        return $this->render('backoffice/user/index.html.twig', [
//            'utilisateurs' => $utilisateurRepository->findAll(),
//
//        ]);
//    }

//    #[Route('/backoffice/list', name: 'app_utilisateur_index', methods: ['GET'])]
//    public function indexx(UtilisateurRepository $utilisateurRepository): Response
//    {
//        return $this->render('backoffice/user/index.html.twig', [
//            'utilisateurs' => $utilisateurRepository->findAll(),
//        ]);
//    }


    #[Route('/backoffice/home', name: 'app_utilisateur_backoffice', methods: ['GET'])]
    public function backoffice(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('frontoffice/backoffice.html.twig', [
            // 'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }
    #[Route('/patients', name: 'app_patients')]
    public function showPatients(UtilisateurRepository $utilisateurRepository): Response
    {
        $patients = $utilisateurRepository->findByRole("PATIENT");

        return $this->render('backoffice/patients/index.html.twig', [
            'patients' => $patients,
        ]);
    }
    #[Route('/doctors', name: 'app_doctors')]
    public function showDoctors(UtilisateurRepository $utilisateurRepository): Response
    {
        $doctors = $utilisateurRepository->findByRole("DOCTOR");

        return $this->render('backoffice/doctors/index.html.twig', [
            'doctors' => $doctors,
        ]);
    }
    #[Route('/pharmacies', name: 'app_pharmacies')]
    public function showPharmacies(UtilisateurRepository $utilisateurRepository): Response
    {
        $pharmacies = $utilisateurRepository->findByRole("PHAMACIEN");

        return $this->render('backoffice/pharmacies/index.html.twig', [
            'pharmacies' => $pharmacies,
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
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
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
    #[Route('/{id}/front/edit', name: 'app_utilisateur_front_edit', methods: ['GET', 'POST'])]
    public function editfrontoffice(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_hello', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/user/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

#[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
        // Fetch all password reset requests associated with the user
        $resetRequests = $entityManager->getRepository(ResetPasswordRequest::class)->findBy([
            'user' => $utilisateur,
        ]);

        // Loop over the requests and remove each one
        foreach ($resetRequests as $request) {
            $entityManager->remove($request);
        }

        // Flush the changes to the database
        $entityManager->flush();

        // Now you can safely delete the user
        $entityManager->remove($utilisateur);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
}



}
