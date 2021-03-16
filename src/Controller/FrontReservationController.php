<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailerService;
use App\Entity\Locataire;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppartementRepository;


class FrontReservationController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    
    /**
     * @Route("/reservation/{id}", name="reservation")
     */
    public function reservation($id, Request $request, MailerService $mailerService, AppartementRepository $apparts)
    {
        $appart = $apparts->find($id);
        $locataire = new Locataire;            
        $form = $this->createForm(ReservationType::class, $locataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $locataire->setLoyer($appart->getLoyerEtudiant());    
        $locataire->addAppartement($appart);
        //$date = date("Y-m-d H:i:s");

       // $locataire->setDateReservation($date);
            
        $this->entityManager->persist($locataire);
        $this->entityManager->flush();
            

//        // on récupère les données du formulaire
//        $formData = $form->getData();
//        
//        //envoi du mail
//        $mailerService->send($formData);
//        
        $this->addFlash(
          'notice',
          ' Votre demande réservation a bien été prise en compte, nous vous répondrons dans les plus bref délais.'
        );
 
        return $this->redirectToRoute('reservation', ['id' => $id]);
        }

        return $this->render('front_reservation/reservation.html.twig', [
           'form' => $form->createView()
        ]);
    }
}
