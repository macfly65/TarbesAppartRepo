<?php

namespace App\Controller;

use App\Service\MailerService;
use App\Entity\ContactHistorique;
use App\Repository\ContactHistoriqueRepository;
use App\Form\ContactHistoriqueType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class FrontContactController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/contact/", name="front_contact")
     */
    public function index(Request $request, MailerService $mailerService)
    {

        $contactHisto = new ContactHistorique;
        $form = $this->createForm(ContactHistoriqueType::class, $contactHisto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contactHisto);
            $this->entityManager->flush();

            // on récupère les données du formulaire
            $formData = $form->getData();

            //envoi du mail
            $mailerService->send($formData);

            $this->addFlash(
                'notice',
                ' Votre email a bien été envoyé.'
            );

            return $this->redirectToRoute('front_contact');
        }

        return $this->render('front_contact/index.html.twig', [
            'formContact' => $form->createView()
        ]);
    }
}
