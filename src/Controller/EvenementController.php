<?php

namespace App\Controller;

use App\Entity\CommentEvent;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Form\CommentFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EvenementRepository;
use App\Repository\RatingRepository;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    // #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    // public function index(EvenementRepository $evenementRepository): Response
    // {
    //     return $this->render('evenement/index.html.twig', [
    //         'evenements' => $evenementRepository->findAll(),
    //     ]);
    // }
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
public function index(Request $request, EvenementRepository $evenementRepository): Response
{
    $query = $request->query->get('query');

    if ($query) {
        $evenements = $evenementRepository->search($query);
    } else {
        $evenements = $evenementRepository->findAll();
    }



    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements,
        
    ]);
}

    #[Route('/listeventfront', name: 'app_evenement_front', methods: ['GET'])]
    public function listEvenetFront(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    {

        $evenements = $evenementRepository->findAll();
        $query = $evenementRepository->findAll();
        $evenements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('evenement/eventsFront.html.twig', [
            'evenements' => $evenements,
            'pagination_template' => 'pagination.html.twig'
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $newFilename = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('EventImage_directory'),
                    $newFilename
                );
    
                $evenement->setImage($newFilename);
            }
            $evenement = $form->getData();
            $evenement->setAdresse($request->get('adresse'));
            $evenement->setLongitude($request->get('longitude'));
            $evenement->setLatitude( $request->get('latitude'));
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    // public function show(Evenement $evenement): Response
    // {
    //     return $this->render('evenement/show.html.twig', [
    //         'evenement' => $evenement,
    //     ]);
    // }







#[Route('/evenement/{id}', name: 'app_evenement_show', methods: ['GET', 'POST'])]
public function show(Request $request, Evenement $evenement, RatingRepository $ratingRepository, EntityManagerInterface $entityManager): Response
{
    if (!$evenement) {
        throw $this->createNotFoundException('The evenement does not exist');
    }

    $averageRating = $ratingRepository->calculateAverageRating($evenement);
    $comment = new CommentEvent();
    $commentForm = $this->createForm(CommentFormType::class, $comment);
    $commentForm->handleRequest($request);
    $error = null;

    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $comment->setEventComment($evenement);
        $comment->setCreatedAt(new \DateTime());
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_evenement_show', ['id' => $evenement->getId()]);
    }

    return $this->render('evenement/show.html.twig', [
        'evenement' => $evenement,
        'averageRating' => $averageRating,
        'commentForm' => $commentForm->createView(),
        'error' => $error,
    ]);
}











    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $newFilename = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('EventImage_directory'),
                    $newFilename
                );
    
                $evenement->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

   
    // public function supprimer($id, EntityManagerInterface $entityManager)
    // {
    //     // $em= $this->getDoctrine()->getManager();
    //     $evenement=$entityManager->getRepository( Evenement::class)->find($id);
    //     $entityManager->remove($evenement);
    //     $entityManager->flush();
    //     return $this->redirectToRoute( "app_evenement_index");

    // }
    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }







// /**
//  * @Route("/search/evenements", name="search_evenements")
//  */
// public function searchEvenements(Request $request, EvenementRepository $evenementRepository): JsonResponse
// {
//     $keyword = $request->query->get('keyword');
//     $evenements = $evenementRepository->findByKeyword($keyword);

//     // Map the programmes to the structure expected by your JavaScript function
//     $evenementsArray = array_map(function ($evenement) {
//         return [
//             'id' => $evenement->getId(),
//             'nom' => $evenement->getNom(),
//             'description' => $evenement->getDescription(),
//             'dateEvent' => $evenement->getDateEvent() ? $evenement->getDateEvent()->format('Y-m-d') : null,
//             'image' => $evenement->getImage(), // Make sure this is the correct field name and it contains just the filename
//         ];
//     }, $evenements);

//     return $this->json($evenementsArray);
// }

/**
 * @Route("/search/evenements", name="search_evenements")
 */
public function searchEvenements(Request $request, EvenementRepository $evenementRepository): JsonResponse
{
    $keyword = $request->query->get('keyword');
    $evenements = $evenementRepository->findByKeyword($keyword);

    // Map the programmes to the structure expected by your JavaScript function
    $evenementsArray = array_map(function ($evenement) {
        return [
            'id' => $evenement->getId(),
            'nom' => $evenement->getNom(),
            'description' => $evenement->getDescription(),
            'dateEvent' => $evenement->getDateEvent() ? $evenement->getDateEvent()->format('Y-m-d') : null,
            'image' => $evenement->getImage(), // Make sure this is the correct field name and it contains just the filename
        ];
    }, $evenements);

    return $this->json($evenementsArray);
}

}
