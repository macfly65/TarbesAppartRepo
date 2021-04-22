<?php

namespace App\Controller;

use App\Entity\ContactHistorique;
use App\Repository\LocataireRepository;
use App\Form\ContactHistoriqueType;
use App\Repository\AppartementMediasRepository;
use App\Service\FlashNotificationAjax;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\loca;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Repository\AppartementRepository;
use App\Service\GeneratePdf;
use setasign\Fpdi\Fpdi;


class ExtranetController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/extranet", name="extranet")
     */
    public function index(GeneratePdf $generatePdf)
    {
        $user = $this->security->getUser();

        return $this->render('extranet/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/extranet/tutoriel", name="extranetTutoriel")
     */
    public function tutoriel()
    {
        $user = $this->security->getUser();


        return $this->render('extranet/tutoriel.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/extranet/FAQ", name="extranetFAQ")
     */
    public function FAQ()
    {
        $user = $this->security->getUser();


        return $this->render('extranet/FAQ.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/extranet/contact", name="extranetContact")
     */
    public function contact(Request $request, MailerService $mailerService)
    {
        $user = $this->security->getUser();
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

            return $this->redirectToRoute('extranetContact');
        }

        return $this->render('extranet/contact.html.twig', [
            'formContact' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/extranet/sign", name="extranetSign")
     */
    public function sign()
    {
        $user = $this->security->getUser();




        return $this->render('extranet/sign.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/extranet/ajaxgetSign", name="axgetSign", options={"expose"=true})
     */
    public function ajaxGetSign(LocataireRepository $locataireRepository, Request $request) {

        if(isset($_POST['dataSign'])) {
            $dataSign = $_POST['dataSign'];
            $locataire = $locataireRepository->find('16');
            // $locataire->setSign($_POST['valAlt']);
             $locataire->setSign($dataSign);

            $this->entityManager->persist($locataire);
            $this->entityManager->flush();
        }

            return new JsonResponse([
            'status' => true,
        ]);
    }



    //sytème generation quittance de loyer a revoir
    // a deplacer dans le service Generate Pdf
    /**
     * @Route("/extranet/getQuittance/{mois}", name="extranet_get_guittance")
     */
    public function generateQuittance($mois)
    {
        $user = $this->security->getUser();
        $locataire = $user->getLocataire();
        $appart = $locataire->getAppartements()->getValues();
        $timeDeb = date('d/m/y h:m',strtotime("-$mois month"));
        $timeEnd = date("d/m/y h:m", strtotime("-$mois month"));


        $data['dateDebutQuitance'] = \DateTime::createFromFormat("d/m/Y H:i", $timeDeb);
        $data['dateFinQuitance'] = \DateTime::createFromFormat("d/m/Y H:i",$timeEnd);
        $data['datePaiement'] =  \DateTime::createFromFormat("d/m/Y H:i","11/01/2021 15:00");

        $datedateDebutQuitance = date('d-m-y',$data['dateDebutQuitance']->getTimestamp());
        $datedateFinQuitance = date('d-m-y', $data['dateFinQuitance']->getTimestamp());
        $datePaiement = date('d-m-y', $data['datePaiement']->getTimestamp());
        $today = date('d-m-y');

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/admin_pdf/quittance.html.twig', [
            'title'  => "Welcome to our PDF Test",
            'appart' => $appart,
            'locataire' => $locataire,
            'today' => $today,
            'datePaiement' => $datePaiement,
            'datedateFinQuitance' => $datedateFinQuitance,
            'datedateDebutQuitance' => $datedateDebutQuitance,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf/quittance';
        $month = date('F',$data['dateDebutQuitance']->getTimestamp());
        $pdfName = "quittance-".$locataire->getNom()."-".$month.'.pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/'.$pdfName;

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($pdfName, [
            "Attachment" => true
        ]);

        return $this->render('extranet/index.html.twig', [
            'user' => $user,
        ]);
    }


   }


