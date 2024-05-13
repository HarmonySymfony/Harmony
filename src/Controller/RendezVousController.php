<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponseResponse;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/rendez/vous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository ): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }
    
    
    #[Route('/liste', name: 'app_rendez_vous_liste', methods: ['GET'])]
    public function liste(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/RendezVousliste.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }
    #[Route('/statistics',name: 'app_statistics', methods: ['GET'])]
public function statistics(RendezVousRepository $rendezVousRepository): Response
{
    $totalRendezVous = count($rendezVousRepository->findAll());
    $rendezVous = $rendezVousRepository->findAll();
    $rendezVousByMonth = array_fill(1, 12, 0);

    
    foreach ($rendezVous as $rendezVous) {
        // Extraire le mois de la chaîne de date
        $month = date('n', strtotime($rendezVous->getDate()));
        $rendezVousByMonth[$month]++;
    }
    
    return $this->render('rendez_vous/statistics.html.twig', [
        'totalRendezVous' => $totalRendezVous,
        'rendezVousData' => array_values($rendezVousByMonth),
    ]);
}
    
  


    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezVou);
            $entityManager->flush();
            flash()->addSuccess('Votre reservation est ajouté avec succés');

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }
        
        

        return $this->renderForm('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }
    #[Route('/new1', name: 'app_rendez_vous_new1', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezVou);
            $entityManager->flush();

            return $this->redirectToRoute('app_rendez_vous_liste', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/new1.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rendezVou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/pdf/{id}', name: 'rendezVous_pdf', methods: ['GET'])]
    public function generatePdf(RendezVousRepository $rendezVousRepository,$id): Response
    {
    $rendezVous = $rendezVousRepository->findById($id);
    $rendezVous=$rendezVous[0];

    $html = $this->renderView('rendez_vous/rendezVous_pdf.html.twig', [
        'rendez_vou' => $rendezVous,
    ]);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfContent = $dompdf->output();
    return new Response($pdfContent, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="rendezVous.pdf"',
    ]);}
    #[Route('/recherche/{id}', name: 'app_recherche', methods: ['GET'])]

    public function recherche(RendezVousRepository $rendezVousRepository ,Request $request ): Response
    { 
      // Récupérer le terme de recherche depuis la requête
     
      $sortField = 'nom'; // Replace 'nom' with the correct field name
      $sortOrder = 'ASC'; // or 'DESC'
      $searchTerm = $request->query->get('id');
      $rendezVous = $rendezVousRepository->searchAndSort($searchTerm, $sortField, $sortOrder); // Appel de la méthode de recherche et de tri
       
      // Passez les compétitions au template Twig pour affichage
       return $this->render('rendez_vous/RendezVousliste.html.twig', [
        'rendez_vouses' => $rendezVous,
    ]);}

   
   
    #[Route('/rendez/vous/events', name: 'rdv_events', methods: ['GET'])]
public function rdvEvents(RendezVousRepository $RendezVousRepository): Response
{
    $rdvs = $RendezVousRepository->findAll();
    $events = [];

    foreach ($rdvs as $rdv) {
        // Convertir la date en objet DateTime si nécessaire
        $date = $rdv->getDate();
        $startDateTime = new \DateTime($date);

        // Formater la date et l'heure
        $formattedDateTime = $startDateTime->format('Y-m-d\TH:i:s');

        // Construire le titre incluant le prénom du rendez-vous
        $title = 'RDV: ' . $rdv->getPrenom();

        $events[] = [
            'title' => $title,
            'start' => $formattedDateTime
        ];
    }

    return $this->json($events);
}


}
