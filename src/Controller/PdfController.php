<?php

namespace App\Controller;

use App\Form\QuittanceFormType;
use App\Repository\AppartementRepository;
use App\Repository\LocataireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends AbstractController
{


    /**
     * @Route("/admin/quittance", name="admin_quittance")
     */
    public function index(AppartementRepository $appartRepo, Request $request)
    {

        // Gestion des filtres
        $form = $this->createForm(QuittanceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if($data['numAppart'] != "" ) {
                $appart = $appartRepo->findOneBy(['numero' => $data['numAppart']]);
                $locataire = $appart->getLocataire()->toArray();
                $this->generateQuittance($appart, $locataire['0'],$data);
            }
        }

        return $this->render('admin/admin_pdf/index.html.twig', [
            'form'=> $form->createView()
        ]);
    }


    /**
     * @Route("/admin/generateQuittance", name="generateQuittance")
     */
        public function generateQuittance($appart, $locataire, $data)
    {

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

        // Send some text response
        return new Response("The PDF file has been succesfully generated !");
    }
}
