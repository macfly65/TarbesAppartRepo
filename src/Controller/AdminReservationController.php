<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\DocumentLocataire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocataireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailerService;
use App\Service\GeneratePdf;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AdminReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    
    /**
     * @Route("/admin/reservation", name="admin_reservation")
     */
    public function index(LocataireRepository $locataire)
    {
     $reservations = $locataire->findAll();

        return $this->render('admin/admin_reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    
        /**
     * @Route("/admin/reservation/fiche/{id}", name="admin_reservation_fiche")
     */
    public function ficheReservation($id, LocataireRepository $locataire)
    {
     $ficheReservation = $locataire->find($id);

        return $this->render('admin/admin_reservation/reservation_fiche.html.twig', [
            'ficheReservation' => $ficheReservation,
        ]);
    }
    
    
     /**
     * @Route("/admin/reservation/validReservation/{id}", name="admin_valid_Reservation")
     */
    public function validReservation($id, LocataireRepository $locataire, MailerService $mailerService, UserPasswordEncoderInterface $encoder, GeneratePdf $GeneratePdf)
    {
        $locataire = $locataire->find($id);
        $user = new User();
        $docLocataire = new documentLocataire ();

        $originPassword = uniqid();
        $encoded = $encoder->encodePassword($user, $originPassword);

        $user->setPassword($encoded);
        $user->setNom($locataire->getNom());
        $user->setPrenom($locataire->getPrenom());
        $user->setRoles(["ROLE_LOC"]);
        $user->setEmail($locataire->getEmail());
        $user->setPasswordVerify($originPassword);

        $locataire->setUser($user);
        $locataire->setStatut(1);

        $docLocataire->setLocataire($locataire);
        $docLocataire->setType('bail');

     $this->entityManager->persist($user);
     $this->entityManager->persist($locataire);
     $this->entityManager->persist($docLocataire);
     $this->entityManager->flush();

        //on génère un bail
     $GeneratePdf->generateBail($docLocataire->getId(), $locataire);
     $this->sendEmailLocataire($id, $user, $locataire, $mailerService);
     
      $this->addFlash(
       'notice',
       'La réservation a bien été validé, un email de confirmation a été envoyé au locataire.'
      );
      
     return $this->redirectToRoute('admin_reservation_fiche', ["id" => $id]);
    }
    
     /**
     * @Route("/admin/reservation/anuleReservation/{id}", name="admin_anule_reservation")
     */
    public function anuleReservation($id, LocataireRepository $locataire, MailerService $mailerService)
    {
     $reservation = $locataire->find($id);
     $reservation->setStatut(3);
     
     $this->entityManager->persist($reservation);
     $this->entityManager->flush();

     $this->sendEmailLocataire($id, $locataire, $mailerService);
     
      $this->addFlash(
       'notice',
       'La réservation a bien été anulé, un email a été envoyé au locataire.'
      );
      
     return $this->redirectToRoute('admin_reservation_fiche', ["id" => $id]);
    }
    
       /**
     * @Route("/sendEmailLocataire/{id}", name="admin_send_notification_reservation")
     */
    public function sendEmailLocataire($id, $user, $locataire, $mailerService)
    {
     $statutResa = $locataire->getStatut();
        if($statutResa == 1){   //résa validé
        $mailerService->sendNotificationReservation($locataire, $user);
     }else{             //résa annulé
        $mailerService->sendNotificationReservation($locataire, $user);
     }

     return $this->redirectToRoute('admin_reservation_fiche', ["id" => $id]);
    }


}
