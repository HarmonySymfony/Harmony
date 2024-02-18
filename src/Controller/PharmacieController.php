<?php

namespace App\Controller;

use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pharmacie')]
class PharmacieController extends AbstractController
{
    #[Route('/', name: 'app_pharmacie_index', methods: ['GET'])]
    public function index(PharmacieRepository $pharmacieRepository): Response
    {
        return $this->render('pharmacie/index.html.twig', [
            'pharmacies' => $pharmacieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmacie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pharmacie = new Pharmacie();
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pharmacie);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmacie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacie/new.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmacie_show', methods: ['GET'])]
    public function show(Pharmacie $pharmacie): Response
    {
        return $this->render('pharmacie/show.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmacie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pharmacie $pharmacie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmacie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacie/edit.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmacie_delete', methods: ['POST'])]
    public function delete(Request $request, Pharmacie $pharmacie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pharmacie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pharmacie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmacie_index', [], Response::HTTP_SEE_OTHER);
    }
}
