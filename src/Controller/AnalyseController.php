<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;

#[Route('/analyse')]
class AnalyseController extends AbstractController
{   


    #[Route('/', name: 'app_analyse_index', methods: ['GET'])]
    public function index(AnalyseRepository $analyseRepository,Request $request,PaginatorInterface $paginator): Response
    {   
        
        $analyses = $analyseRepository->findAll();
            
        $analyses = $paginator->paginate(
                    $analyses,
                    $request->query->getInt('page',1),
                    1, 
            );   
            

        

        return $this->render('frontoffice/analyse/index.html.twig', [
            'analyses' => $analyses, 

        ]);
    }
    #[Route("/generate-pdf/{id}", name: "mahmoud_pdf", methods: ['GET'])]
    public function generatePdf($id, AnalyseRepository $analyseRepository): Response
    {
        // Get the subscription plan details
        $analyse = $analyseRepository->find($id);
        $idAnalyse=$analyseRepository->findLaboById($id);


        // Create Dompdf instance
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $writer = new PngWriter();
        $qrCode = QrCode::create('http://127.0.0.1:8000/laboratoires/' . $idAnalyse.'/front')
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $logo = Logo::create('img/logo.png')
            ->setResizeToWidth(20);
        $label = Label::create('')->setFont(new NotoSans(8));

        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
        $qrCodes['simple'] = $writer->write(
            $qrCode,
            null,
            $label->setText('Simple')
        )->getDataUri();

        $qrCode->setForegroundColor(new Color(0, 0, 0));
        $qrCodes['changeColor'] = $writer->write(
            $qrCode,
            null,
            $label->setText('Color Change')
        )->getDataUri();

        $qrCodeDataUri = $writer->write($qrCode, $logo)->getDataUri();
        
        // Render the Twig template to HTML
        $html = $this->renderView('frontoffice/analyse/pdf.html.twig', [
            'analyse' => $analyse,
            'qrCodeDataUri'=> $qrCodeDataUri

        ]);

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output PDF content
        $pdfOutput = $dompdf->output();

        // Send the PDF as a response
        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Analyse.pdf"'
        ]);
    }
    
    #[Route('/back', name: 'app_analyse_back_index', methods: ['GET'])]
    public function analyseback(AnalyseRepository $analyseRepository): Response
    {
        return $this->render('backoffice/analyse/index.html.twig', [
            'analyses' => $analyseRepository->findAll(),
        ]);
    }

    #[Route('/newanalyse', name: 'app_analyse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $analyse = new Analyse();
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($analyse);
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/analyse/_form.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/showanalyse', name: 'app_analyse_show', methods: ['GET'])]
    public function show(Analyse $analyse): Response
    {
        return $this->render('frontoffice/analyse/show.html.twig', [
            'analyse' => $analyse,
        ]);
    }

    #[Route('/{id}/editanalyse', name: 'app_analyse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/analyse/edit.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/deleteanalyse', name: 'app_analyse_delete', methods: ['POST'])]
    public function delete(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$analyse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($analyse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
    }
}
