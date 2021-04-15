<?php


namespace App\Service;


use Dompdf\Dompdf;
use Dompdf\Options;
use Mailjet\Resources;
use setasign\Fpdi\FpdfTpl;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use setasign\Fpdi\Fpdi;

class GeneratePdf
{
    private $mailer;
    private $templating;
    private $params;


    public function __construct(\Swift_Mailer $mailer, \Twig\Environment $templating, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->params = $params;

    }

    /**
     * @Route("/extranet/getQuittance", name="extranet_get_guittance")
     */
    public function generateQuittance()
    {
        $user = $this->security->getUser();
        $locataire = $user->getLocataire();
        $appart = $locataire->getAppartements()->getValues();

        $data['dateDebutQuitance'] = \DateTime::createFromFormat("d/m/Y H:i", "11/01/2021 15:00");
        $data['dateFinQuitance'] = \DateTime::createFromFormat("d/m/Y H:i", "11/04/2021 15:00");
        $data['datePaiement'] = \DateTime::createFromFormat("d/m/Y H:i", "11/01/2021 15:00");


        $datedateDebutQuitance = date('d-m-y', $data['dateDebutQuitance']->getTimestamp());
        $datedateFinQuitance = date('d-m-y', $data['dateFinQuitance']->getTimestamp());
        $datePaiement = date('d-m-y', $data['datePaiement']->getTimestamp());
        $today = date('d-m-y');

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file

        $html = $this->templating->render('admin/admin_pdf/quittance.html.twig', [
            'title' => "Welcome to our PDF Test",
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
        $publicDirectory = $this->params->get('kernel.project_dir') . '/public/pdf/quittance';
        $month = date('F', $data['dateDebutQuitance']->getTimestamp());
        $pdfName = "quittance-" . $locataire->getNom() . "-" . $month . '.pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath = $publicDirectory . '/' . $pdfName;

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($pdfName, [
            "Attachment" => true
        ]);

        // Send some text response
        return new Response("The PDF file has been succesfully generated !");
    }


    public function getBail()
    {
        $user = $this->security->getUser();
        $locataire = $user->getLocataire();
        $appart = $locataire->getAppartements()->getValues();
        $today = date('d-m-y');


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->templating->render('admin/admin_pdf/bail.html.twig', [
            'title' => "Welcome to our PDF Test",
            'appart' => $appart,
            'locataire' => $locataire
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
        $publicDirectory = $this->params->get('kernel.project_dir') . '/public/pdf/quittance';
        $month = date('F', $today->getTimestamp());
        $pdfName = "bail-" . $locataire->getNom() . "-" . $month . '.pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath = $publicDirectory . '/' . $pdfName;

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($pdfName, [
            "Attachment" => true
        ]);

        // Send some text response
        return new Response("The PDF file has been succesfully generated !");
    }

    public function generateBail($locataire)
    {
        // initiate FPDI
        $pdf = new Fpdi();
        // get document source
        $doc = $this->params->get('kernel.project_dir') . '/public/pdf/origin/BAIL-EVASION.pdf';
        $signature = $this->params->get('kernel.project_dir') . '/public/pdf/signature/signDidier.jpg';
        $signatureTampon = $this->params->get('kernel.project_dir') . '/public/pdf/signature/parafDidier.jpg';


        // set the source file
        $pdf->setSourceFile($doc);

        // set the source file
        $pageCount = $pdf->setSourceFile($doc);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);

            $pdf->AddPage();
            $pdf->useTemplate($tplId, 0, 0, 200);
            $appartement = $locataire->getAppartements()->toArray()[0];

            if ($pageNo == 1) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(30, 162);
                $pdf->Write(0, $locataire->getNom() . ' ' . $locataire->getPrenom());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(25, 190);
                $pdf->Write(0, 'SCI EVASION, 5 rue de l\'eglise, 65390 Sarniguet');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(38, 198);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(30, 210);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(35, 218);
                $pdf->Write(0, '___');

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 2) {

                // now write some text above the imported page
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(67, 40);
                $pdf->Write(0, $appartement->getAdresse());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(50, 48);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(106, 52);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(64, 56);
                $pdf->Write(0, '1998');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(55, 60);
                $pdf->Write(0, $appartement->getSurface());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(73, 64);
                $pdf->Write(0, substr($appartement->getType(), -1));

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(100, 76);
                $pdf->Write(0, 'cuisine equipe');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(106, 88);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(97.5, 100);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(25.5, 119.5);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(25.5, 133.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(25.5, 160.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(25.5, 191);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(90, 223);
                $pdf->Write(0, $locataire->getDateArivee()->format('d/m/y'));

                if ($appartement->getMeuble() == 1) {
                    $meuble = '1 ans';
                } else {
                    $meuble = '3 ans';
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(63, 230);
                $pdf->Write(0, $meuble);

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 3) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(71, 54);
                $pdf->Write(0, $appartement->getLoyerEtudiant());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(85, 66.5);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(28, 74.5);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(84, 78.5);
                $pdf->Write(0, '0 euros');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(28, 82.5);
                $pdf->Write(0, '0 euros');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(78, 86.5);
                $pdf->Write(0, '0 euros');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(26, 108.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(56, 129.5);
                $pdf->Write(0, $locataire->getDateArivee()->format('d/m/y'));

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(87, 134);
                $pdf->Write(0, 'REFERENCE IRL');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(100.5, 146);
                $pdf->Write(0, 'x');

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 4) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(30, 13);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(164, 48);
                $pdf->Write(0, $appartement->getCharge());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(110, 55);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(39.5, 77.3);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(161, 80);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(83, 89);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(68, 105);
                $pdf->Write(0, 'Mensuelle');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(64.5, 109);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(70.5, 113);
                $pdf->Write(0, 'Entre le 1er et le 5 du mois');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(70.5, 115);
                $pdf->Write(0, '___');

                $total = $appartement->getLoyerEtudiant() + $appartement->getCharge();

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(26, 124);
                $pdf->Write(0, 'Loyer + charges = ' . $total);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(113, 156.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(97.5, 166.5);
                $pdf->Write(0, 'x');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(78.5, 196.5);
                $pdf->Write(0, 'Peinture, nettoyage ');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(131.5, 214.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(131.5, 214.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(44.5, 241.5);
                $pdf->Write(0, '___');

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 5) {
                if ($appartement->getMeuble() == "meuble") {
                    $caution = $appartement->getLoyerEtudiant() * 2 . 'euros';
                } else {
                    $caution = $appartement->getLoyerEtudiant() . 'euros';
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(129.5, 22);
                $pdf->Write(0, $caution);

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 6) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(102.5, 19);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(40.5, 32);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(155.5, 56);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(120.5, 73);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(72, 85.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(155.5, 106.5);
                $pdf->Write(0, '___');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(119.5, 123.5);
                $pdf->Write(0, '___');

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 7) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 8) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 9) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(22, 28);
                $pdf->Write(0, 'tralala');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(25, 157.5);
                $pdf->Write(0, $locataire->getDateArivee()->format('d/m/y'));

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(110, 158);
                $pdf->Write(0, 'Tarbes');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(26, 161.5);
                $pdf->Write(0, '2');

                $pdf->Image($signature, 19, 185.5, 55, '', '', '', '', false, 300);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(90, 191.5);
                $pdf->Write(0, '2');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(160, 191.5);
                $pdf->Write(0, '2');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(26, 213.5);
                $pdf->Write(0, '2');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(26, 217.5);
                $pdf->Write(0, '2');
            }
            if ($pageNo == 10) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 11) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 12) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 13) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 14) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 15) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 16) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 17) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 18) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 19) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 20) {
                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
        }

        //$pdf->Output();

        //register
        $fileName = $locataire->getId() . '-bail-habitation.pdf';
        $pdf->Output('F', $this->params->get('kernel.project_dir') . '/public/pdf/bail/' . $fileName);
    }

    public function generateAttestationCaf($locataire)
    {
        // initiate FPDI
        $pdf = new Fpdi();
        // get document source
        $doc = $this->params->get('kernel.project_dir') . '/public/pdf/origin/attestationCaf.pdf';
        $signature = $this->params->get('kernel.project_dir') . '/public/pdf/signature/signDidier.jpg';

        // set the source file
        $pdf->setSourceFile($doc);

        // set the source file
        $pageCount = $pdf->setSourceFile($doc);

        $tplId = $pdf->importPage(1);

        $pdf->AddPage();
        $pdf->useTemplate($tplId, 0, 0, 200);
        $appartement = $locataire->getAppartements()->toArray()[0];
        $today = date('d m  Y');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(113, 38);
        $pdf->Write(0, 'SCI EVASION');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(21, 43);
        $pdf->Write(0, ' 8   7   9   0   7   8   2   6   9   0   0   0   1   3');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(22, 49);
        $pdf->Write(0, '5 rue de l\'eglise');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(35, 53);
        $pdf->Write(0, '0   6   3    1    5   3    1   8    4    2');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(56, 59);
        $pdf->Write(0, $locataire->getNom() . ' ' . $locataire->getPrenom());

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(48, 63);
        $pdf->Write(0, $locataire->getDateArivee()->format('d  m  Y'));

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(129, 64);
        $pdf->Write(0, '1 rue Gabriel Faure, 65000 Tarbes');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(94, 73.3);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78, 78);
        $pdf->Write(0, $appartement->getSurface());

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(77.5, 83.4);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(19, 98.5);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(64, 99);
        $pdf->Write(0, $appartement->getLoyerEtudiant());

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(118, 108.6);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(118, 108.6);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(74.7, 118.5);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(96.7, 129);
        $pdf->Write(0, $locataire->getDateArivee()->format('d  m  Y'));

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(31.5, 184);
        $pdf->Write(0, 'X');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(20, 237.5);
        $pdf->Write(0, 'Tarbes');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(70, 237);
        $pdf->Write(0, $today);

        $pdf->Image($signature, 145, 233, 40, '', '', '', '', false, 300);

        //$pdf->Output();
        //register
        $fileName = $locataire->getId() . '-attestation-caf.pdf';
        $pdf->Output('F', $this->params->get('kernel.project_dir') . '/public/pdf/attestationCaf/' . $fileName);
    }


}


