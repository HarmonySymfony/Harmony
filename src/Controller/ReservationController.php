<?php

namespace App\Controller;

use App\Entity\CommentEvent;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\CommentFormType;
use App\Repository\RatingRepository;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\False_;
use ProxyManager\Exception\ExceptionInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }









    // /**
    //  * @Route("/reserverEvent/{id}", name="reserverEvent")
    //  */
    // public function reserverEvent(Request $req, $id, EntityManagerInterface $entityManager)
    // {
    //     $evenement = $entityManager->getRepository(Evenement::class)->find($id);
    //     $reservation = new Reservation();
        

    //     if ($req->isMethod("post")) {
    //         $reservation->setIdevent($evenement);
    //         $nbredeticketDemandé = (int)($req->get('nbrplace'));
    //         $reservation->setApprouve(0);


    //         if ($nbredeticketDemandé <= $evenement->getPlaceDispo()) {
    //             $reservation->setNbrPlace($nbredeticketDemandé);
    //             $evenement->setPlaceDispo(($evenement->getPlaceDispo()) - (int)($req->get('nbrplace')));
    //         } else {
    //             return $this->redirectToRoute('reserverEvent', array('id' => $id));
    //         }
    //         try {
    //             $entityManager->persist($reservation);
    //             $entityManager->flush();
    //             return $this->redirectToRoute('app_evenement_show', ['id' => $id]);
    //         } catch (ExceptionInterface $e) {
    //         }
    //     }

    //     return $this->render('evenement/show.html.twig', array('evenement' => $evenement));
    // }






    /**
     * @Route("/reserverEvent/{id}", name="reserverEvent")
     */
public function reserverEvent(Request $req, $id, EntityManagerInterface $entityManager, RatingRepository $ratingRepository)
{
    $evenement = $entityManager->getRepository(Evenement::class)->find($id);
    $reservation = new Reservation();
    $averageRating = $ratingRepository->calculateAverageRating($evenement);
    $comment = new CommentEvent();
    $commentForm = $this->createForm(CommentFormType::class, $comment);
    $error = null; // Initialize error message variable

    if ($req->isMethod("post")) {
        $nbredeticketDemandé = (int)($req->get('nbrplace'));
        
        if ($nbredeticketDemandé <= $evenement->getPlaceDispo()) {
            $reservation->setIdevent($evenement);
            $reservation->setNbrPlace($nbredeticketDemandé);
            $reservation->setApprouve(0);
            $evenement->setPlaceDispo($evenement->getPlaceDispo() - $nbredeticketDemandé);

            try {
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('app_evenement_show', ['id' => $id]);
            } catch (FileNotFoundException $e) {
            }
        } else {
            $error = "le nombre de ticket est plus grand que le nombre de places";
        }
    }

    return $this->render('evenement/show.html.twig', [
        'evenement' => $evenement,
        'averageRating' => $averageRating,
        'commentForm' => $commentForm->createView(),
        'error' => $error, // Pass the error message to your template
    ]);
}








    /**
     * @Route("/listreservation/{id}", name="afficherReservation")
     */
    public function listReservationByEvent($id, EntityManagerInterface $entityManager)
    {
        $event = $entityManager->getRepository(Evenement::class)->find($id);
        $listReservation = $entityManager->getRepository(Reservation::class)->findBy(array('idevent' => $event));
        $listEventsBack = $entityManager->getRepository(Evenement::class)->findAll();

        return $this->render('reservation/listReservation.html.twig', [
            'reservations' => $listReservation,
            'events' => $listEventsBack,
        ]);
    }







    /**
     * @Route("/listreser", name="listReservationBack")
     */
    public function listReservation(EntityManagerInterface $entityManager)
    {
        $listReservation = $entityManager->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/listReservationBack.html.twig', array('reservations' => $listReservation));
    }






    // /**
    //  * @param int $id
    //  * @Route("/approuverReservation/{id}", name="approuverReservation")
    //  */
    // public function approuverReservation(int $id, EntityManagerInterface $entityManager)
    // {
    //     $reservation = $entityManager->getRepository(Reservation::class)->find($id);

    //     if (!$reservation) {
    //         throw $this->createNotFoundException('Reservation not found for id ' . $id);
    //     }

    //     $reservation->setApprouve(true);

    //     $entityManager->flush();

    //     return $this->redirectToRoute('listReservationBack', ['id' => $id]);
    // }





    /**
 * @param int $id
 * @Route("/approuverReservation/{id}", name="approuverReservation")
 */
public function approuverReservation(int $id, EntityManagerInterface $entityManager, MailerInterface $mailer)
{
    $reservation = $entityManager->getRepository(Reservation::class)->find($id);

    if (!$reservation) {
        throw $this->createNotFoundException('Reservation not found for id ' . $id);
    }

    $reservation->setApprouve(true);

    // Send email notification
    $email = (new TemplatedEmail())
        ->from('pidev0420@gmail.com') //connecteduser
        ->to('amriraed826@gmail.com') // userreservationid
        ->subject('Reservation Approved')
        ->htmlTemplate('reservation_approved.html.twig')
        ->context([
            'reservation' => $reservation,
        ]);

    $mailer->send($email);

    $entityManager->flush();

    return $this->redirectToRoute('listReservationBack', ['id' => $id]);
}
}
