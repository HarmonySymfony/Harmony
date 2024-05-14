<?php

namespace App\Controller;

use App\Entity\Laboratoires;
use App\Form\LaboratoiresType;
use App\Repository\LaboratoiresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/laboratoires')]
class LaboratoiresController extends AbstractController
{   private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app_laboratoires_index', methods: ['GET'])]
    public function index(LaboratoiresRepository $laboratoiresRepository): Response
    {
        return $this->render('backoffice/laboratoires/index.html.twig', [
            'laboratoires' => $laboratoiresRepository->findAll(),
        ]);
    }

    
    #[Route('/front', name: 'app_laboratoires_front_index', methods: ['GET'])]
    public function labofront(LaboratoiresRepository $laboratoiresRepository, Request $request, PaginatorInterface $paginator): Response
    {
    $laboratoires = $laboratoiresRepository->findAll();

    return $this->render('frontoffice/laboratoires/index.html.twig', [
        'laboratoires' => $laboratoires,
        
    ]);
}



    #[Route('/new', name: 'app_laboratoires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $laboratoire = new Laboratoires();
        $form = $this->createForm(LaboratoiresType::class, $laboratoire);
        $form->handleRequest($request);
        echo "submitting";
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($laboratoire);
            $entityManager->flush();

            return $this->redirectToRoute('app_laboratoires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/laboratoires/new.html.twig', [
            'laboratoire' => $laboratoire,
            'form' => $form,
        ]);
    }
    
    
    #[Route('/{id}', name: 'app_laboratoires_show', methods: ['GET'])]
    public function show(Laboratoires $laboratoire): Response
    {
        return $this->render('backoffice/laboratoires/show.html.twig', [
            'laboratoire' => $laboratoire,
        ]);
    }
    #[Route('/{id}/front', name: 'app_laboratoires_show-front', methods: ['GET'])]
    public function showFront(Laboratoires $laboratoire): Response
    {
        return $this->render('frontoffice/laboratoires/show.html.twig', [
            'laboratoire' => $laboratoire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_laboratoires_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Laboratoires $laboratoire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LaboratoiresType::class, $laboratoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_laboratoires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/laboratoires/edit.html.twig', [
            'laboratoire' => $laboratoire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_laboratoires_delete', methods: ['POST'])]
    public function delete(Request $request, Laboratoires $laboratoire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$laboratoire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($laboratoire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_laboratoires_index', [], Response::HTTP_SEE_OTHER);
    }
}
