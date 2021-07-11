<?php

namespace App\Service;

use App\Repository\EdlRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Mailjet\Resources;
use setasign\Fpdi\FpdfTpl;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PDF_Rotate;

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

    public function generateAttestationRemiseCle($locataire)
    {

        $pdf = new Fpdi();
        $appartement = $locataire->getAppartements()->getValues();


        // get document source
        $doc = $this->params->get('kernel.project_dir') . '/public/pdf/origin/attestation-remise-cle.pdf';
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

            if ($pageNo == 1) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(65, 94);
                $pdf->Write(0, $locataire->getNom() . ' ' . $locataire->getPrenom());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(155, 99.5);
                $pdf->Write(0, $appartement[0]->getNumero());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(65, 104);
                $pdf->Write(0, $locataire->getDateArivee()->format('d      m       Y'));

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(100, 113.5);
                $pdf->Write(0, $locataire->getnumCleAppart());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(130, 123.5);
                $pdf->Write(0, $locataire->getnumCleIntratone());


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(130, 199.5);
                $pdf->Write(0, 'Le ' . $locataire->getDateArivee()->format('d/m/Y'));


                $pdf->Image($signature, 60, 205, 55, '', '', '', '', false, 300);
            }
        }
        $fileName = $locataire->getId() . '-attestation-remise-cle.pdf';
        $pdf->Output('F', $this->params->get('kernel.project_dir') . '/public/pdf/attestationRemiseCle/' . $fileName);
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
        $appartement = $locataire->getAppartements()->toArray()[0];

        if ($appartement->getMeuble() == 'meuble') {

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

                if ($pageNo == 1) {
                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(30, 162);
                    $pdf->Write(0, $locataire->getNom() . ' ' . $locataire->getPrenom() . ' nee le ' . $locataire->getDateNaissancce()->format('d/m/y') . ' domicilie au ' . $locataire->getAdresse() . ', ' . $locataire->getVille() . ' ' . $locataire->getCodePostal());

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
                    $pdf->Write(0, '___');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(28, 82.5);
                    $pdf->Write(0, '___');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(78, 86.5);
                    $pdf->Write(0, '__');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(26, 108.5);
                    $pdf->Write(0, '___');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(56, 129.5);
                    $pdf->Write(0, '15 avril 2021');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(87, 134);
                    $pdf->Write(0, '130,69');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(100.5, 146);
                    $pdf->Write(0, 'x');

                    $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
                }
                if ($pageNo == 4) {
                    //////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////   Calcul des prix  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

                    // LOYER PRORATA //
                    // nombre de jours dans  le mois
                    $nbrJourMois = cal_days_in_month (1, $locataire->getDateArivee()->format('m') , $locataire->getDateArivee()->format('Y') );

                    // nombre jour resté
                    $nbrJoursLocation = $nbrJourMois - $locataire->getDateArivee()->format('j');

                    //prix du loyer par jour
                    $loyerJour = $appartement->getLoyerEtudiant()/$nbrJourMois;

                    //loyer prorata
                    $totalLoyerProrata = $loyerJour * $nbrJoursLocation;

                    // mise en forme
                    $longeurChaineLoyer = strlen($totalLoyerProrata)-6;
                    $totalLoyerProrata = substr("$totalLoyerProrata",0, -$longeurChaineLoyer);

                    // LOYER PRORATA //
                    $chargeJour = $appartement->getCharge()/$nbrJourMois;

                    $totalChargeProrata = $chargeJour * $nbrJoursLocation;

                    // mise en forme
                    $longeurChaineCharge = strlen($totalChargeProrata)-5;
                    $totalChargeProrata = substr("$totalChargeProrata",0, -$longeurChaineCharge);

                    // Total Due //
                    $total = $totalLoyerProrata + $totalChargeProrata;


                    //loyer + charges
                    $loyerEtCharges = $appartement->getLoyerEtudiant() + $appartement->getCharge();

                    /////////////////////////////////  Fin  Calcul des prix  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
                    /////////////////////////////////////////////////////////////////////////////////////////

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
                    $pdf->Write(0, 'Arrive le '. $locataire->getDateArivee()->format('d/m/y') .' : 480 euros (loyer) '. $totalChargeProrata .' euros (charges) '.$nbrJoursLocation.' jours, soit '.$total.' euros');

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
                    $pdf->SetXY(79, 196.5);
                    $pdf->Write(0, 'Au cours des mois ecoules le logement a ete remis en etat; peinture');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(29, 200.5);
                    $pdf->Write(0, 'flexible de douche, pommeau, lunette wc, mecanisme.');

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
                    $pdf->Write(0, 'Detecteur de fumee en place');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(22, 33);
                    $pdf->Write(0, 'Les chauffages de type "Zibro" ou "Buta Thermix" sont formellement interdit. ');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(22, 38);
                    $pdf->Write(0, 'Si le locataire passe outre cette interdiction et venait a les utiliser, il serait');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(22, 43);
                    $pdf->Write(0, 'seul responsable des desordres ocasionnes du logement par ce type de chauffage (moisissures, etc ...)');

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
        } else {
            // get document source
            $doc = $this->params->get('kernel.project_dir') . '/public/pdf/origin/BAIL-EVASION-NM.pdf';
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

                if ($pageNo == 1) {
                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(30, 161);
                    $pdf->Write(0, 'SCI EVASION, 5 rue de l\'eglise, 65390 Sarniguet');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(30, 209);
                    $pdf->Write(0, $locataire->getNom() . ' ' . $locataire->getPrenom() . ' nee le ' . $locataire->getDateNaissancce()->format('d/m/y') . ' domicilie au ' . $locataire->getAdresse() . ', ' . $locataire->getVille() . ' ' . $locataire->getCodePostal());

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(35, 216);
                    $pdf->Write(0, '___________________________________________________');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(35, 224);
                    $pdf->Write(0, '___________________________________________________');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(35, 232);
                    $pdf->Write(0, '___________________________________________________');

                    $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
                }
                if ($pageNo == 2) {

                    // now write some text above the imported page
                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(67, 38);
                    $pdf->Write(0, $appartement->getAdresse() . ' Appartement numero ' . $appartement->getNumero());

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(50, 47);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(75, 51);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(64, 54);
                    $pdf->Write(0, '1991');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(55, 58);
                    $pdf->Write(0, $appartement->getSurface());

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(73, 62);
                    $pdf->Write(0, substr($appartement->getType(), -1));

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(100, 74);
                    $pdf->Write(0, 'Cuisine equipe sans electromenager');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25, 78);
                    $pdf->Write(0, 'salle de bain avec baignoire');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(83, 87);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25, 91);
                    $pdf->Write(0, 'Radiateur electriques');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25, 115);
                    $pdf->Write(0, 'Cumulus');


                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(97, 111.5);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25.5, 154);
                    $pdf->Write(0, 'Place de parking');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25.5, 185);
                    $pdf->Write(0, 'Hall d\'entree, ascenceur, escalier.');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25.5, 212);
                    $pdf->Write(0, 'Antenne collective.');


                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(88.5, 243);
                    $pdf->Write(0, $locataire->getDateArivee()->format('d/m/y'));


                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(62.5, 250);
                    $pdf->Write(0, '3 ans, trois ans');


                    $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
                }
                if ($pageNo == 3) {

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(70, 78.5);
                    $pdf->Write(0, $appartement->getLoyerEtudiant() . ' euros');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(99.5, 90);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(42, 98.5);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(60, 184);
                    $pdf->Write(0, '15 avril');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(90, 188.5);
                    $pdf->Write(0, '1 er trimestre 2021,  130,69');

                    $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
                }
                if ($pageNo == 4) {
                    //////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////   Calcul des prix  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

                    // LOYER PRORATA //
                    // nombre de jours dans  le mois
                    $nbrJourMois = cal_days_in_month (1, $locataire->getDateArivee()->format('m') , $locataire->getDateArivee()->format('Y') );

                    // nombre jour resté
                    $nbrJoursLocation = $nbrJourMois - $locataire->getDateArivee()->format('j');

                    //prix du loyer par jour
                    $loyerJour = $appartement->getLoyerEtudiant()/$nbrJourMois;

                    //loyer prorata
                    $totalLoyerProrata = $loyerJour * $nbrJoursLocation;

                    // mise en forme
                    $longeurChaineLoyer = strlen($totalLoyerProrata)-6;
                    $totalLoyerProrata = substr("$totalLoyerProrata",0, -$longeurChaineLoyer);

                    // LOYER PRORATA //
                    $chargeJour = $appartement->getCharge()/$nbrJourMois;

                    $totalChargeProrata = $chargeJour * $nbrJoursLocation;

                    // mise en forme
                    $longeurChaineCharge = strlen($totalChargeProrata)-5;
                    $totalChargeProrata = substr("$totalChargeProrata",0, -$longeurChaineCharge);

                    $loyerEtCharges = $appartement->getLoyerEtudiant() + $appartement->getCharge();

                    // Total Due //
                    $total = $totalLoyerProrata + $totalChargeProrata;

                    /////////////////////////////////  Fin  Calcul des prix  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
                    /////////////////////////////////////////////////////////////////////////////////////////

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(107.2, 17.2);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(28, 41);
                    $pdf->Write(0, 'Les charges seront calcule pour l\'eau selon le releve du compteur individuel pour le menage,');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(28, 45);
                    $pdf->Write(0, 'les espaces vert et la minuterie selon le releve de propriete fourni par les services fiscaux ');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(28, 49);
                    $pdf->Write(0, 'ainsi que la taxe d\'ordure menagere');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(168, 65);
                    $pdf->Write(0, '65 euros');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(28, 70.5);
                    $pdf->Write(0, 'soixante cinq euros');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(70, 146.5);
                    $pdf->Write(0, 'Mensuelle');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(45, 150.5);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(63, 149.5);
                    $pdf->Write(0, '_____________');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(73, 154);
                    $pdf->Write(0, 'Entre le 1 er et le 10 du mois');



                    if($locataire->getDateArivee()->format('Y-m-d') != date('Y-m-01')){

                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(28, 166.5);
                        // $pdf->Write(0, 'Arrive le '. $locataire->getDateArivee()->format('d/m/y') .' : '. $totalLoyerProrata .' euros (loyer) '. $totalChargeProrata .' euros (charges) '.$nbrJoursLocation.' jours, soit '.$total.' euros');
                         $pdf->Write(0, 'Arrive le '. $locataire->getDateArivee()->format('Y-m-d') . '==' . date('Y-m-01'));

                    }else{

                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY(28, 166.5);
                        $pdf->Write(0, '' . $appartement->getLoyerEtudiant() .' + ' . $appartement->getCharge() . " = " . $loyerEtCharges . ' euros');
                    }

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(79, 234.5);
                    $pdf->Write(0, 'Au cours des mois ecoules le logement a ete remis en etat; peinture');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(29, 238.5);
                    $pdf->Write(0, 'flexible de douche, pommeau, lunette wc, mecanisme.');


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
                    $pdf->SetXY(165.5, 61);
                    $pdf->Write(0, $caution);

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(25, 66);
                    $pdf->Write(0, 'quatre cent quatre vingt euros');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(21, 137);
                    $pdf->Write(0, 'x');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(21, 144);
                    $pdf->Write(0, '_______________________________________________________________________________________');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(21, 148);
                    $pdf->Write(0, '_______________________________________________________________________________________');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(21, 152);
                    $pdf->Write(0, '_______________________________________________________________________________________');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(21, 156);
                    $pdf->Write(0, '____________________________________________');

                    $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
                }
                if ($pageNo == 6) {


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
                    $pdf->SetXY(22, 29);
                    $pdf->Write(0, 'Detecteur de fumee en place. Les chauffages de type "Zibro" ou "Buta Thermix" sont formellement interdit. ');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(22, 34);
                    $pdf->Write(0, 'Si le locataire passe outre cette interdiction et venait a les utiliser, il serait');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(22, 40);
                    $pdf->Write(0, 'seul responsable des desordres ocasionnes du logement par ce type de chauffage (moisissures, etc ...)');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(27, 161.5);
                    $pdf->Write(0, $locataire->getDateArivee()->format('d/m/y'));

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(27, 166.5);
                    $pdf->Write(0, '2');


                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(110, 161);
                    $pdf->Write(0, 'Tarbes');


                    $pdf->Image($signature, 19, 185.5, 55, '', '', '', '', false, 300);


                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(26, 231.5);
                    $pdf->Write(0, '8');

                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(26, 235.5);
                    $pdf->Write(0, '0');
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
            //register
            $fileName = $locataire->getId() . '-bail-habitation.pdf';
            $pdf->Output('F', $this->params->get('kernel.project_dir') . '/public/pdf/bail/' . $fileName);
        }
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

    public function generateBailParking($locataire)
    {
// initiate FPDI
        $pdf = new Fpdi();
        // get document source
        $doc = $this->params->get('kernel.project_dir') . '/public/pdf/origin/BAIL-PARKING.pdf';
        $signature = $this->params->get('kernel.project_dir') . '/public/pdf/signature/signDidier.jpg';
        $signatureTampon = $this->params->get('kernel.project_dir') . '/public/pdf/signature/parafDidier.jpg';
        $appartement = $locataire->getAppartements()->toArray()[0];

        // set the source file
        $pdf->setSourceFile($doc);

        // set the source file
        $pageCount = $pdf->setSourceFile($doc);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);

            $pdf->AddPage();
            $pdf->useTemplate($tplId, 0, 0, 200);

            if ($pageNo == 1) {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(20, 34);
                $pdf->Write(0, $locataire->getNom() . ' ' . $locataire->getPrenom());

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(100, 34);
                $pdf->Write(0, $locataire->getDateNaissancce()->format('d-m-Y'));

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(145, 34);
                $pdf->Write(0, '---');

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(27, 41);
                $pdf->Write(0, $locataire->getVille());


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(13, 58);
                $pdf->Write(0, '________________________________________________________________________________________' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(13, 64);
                $pdf->Write(0, '________________________________________________________________________________________' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(13, 71);
                $pdf->Write(0, '________________________________________________________________________________________' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(13, 81.5);
                $pdf->Write(0, '________________________________________________________________________________________' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(13, 88);
                $pdf->Write(0, '________________________________________________________________________________________' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(13, 95);
                $pdf->Write(0, '________________________________________________________________________________________' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(135, 120);
                $pdf->Write(0, '_____' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(100, 125);
                $pdf->Write(0, 'Tarbes' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(165, 125);
                $pdf->Write(0, 'Gabriel' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(20, 130);
                $pdf->Write(0, 'Faure' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(73, 130);
                $pdf->Write(0, '1' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(73, 130);
                $pdf->Write(0, '1' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(104, 146);
                $pdf->Write(0, '12' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(154, 146);
                $pdf->Write(0, $locataire->getDateArivee()->format('d-m-Y'));

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(43, 150);
                $pdf->Write(0, '___' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(147, 176);
                $pdf->Write(0, '30 euros,' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(17, 181);
                $pdf->Write(0, 'trente' );

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(29, 186);
                $pdf->Write(0, '1 er' );

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 2) {

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 3) {

                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
            if ($pageNo == 4) {

                $pdf->Image($signature, 35, 242, 55, '', '', '', '', false, 300);


                $pdf->Image($signatureTampon, 193, 284, 10, '', '', '', '', false, 300);
            }
        }

        $fileName = $locataire->getId() . '-bail-parking.pdf';
        $pdf->Output('F', $this->params->get('kernel.project_dir') . '/public/pdf/bail/' . $fileName);
    }

    public function generateEDL($dataEDL,  $edl){
        $pdf = new Fpdi();

        //$pdf = new PDF_Rotate('P','mm',array(225.37,261.719));
        $pdf = new PDF_Rotate();


        // get document source
        $doc = $this->params->get('kernel.project_dir') . '/public/pdf/origin/ETAT-DES-LIEUX.pdf';
        $signature = $this->params->get('kernel.project_dir') . '/public/pdf/signature/signDidier.jpg';
        $signatureTampon = $this->params->get('kernel.project_dir') . '/public/pdf/signature/parafDidier.jpg';

        // set the source file
        $pdf->setSourceFile($doc);

        // set the source file
        $pageCount = $pdf->setSourceFile($doc);


        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);

            $pdf->AddPage();
            $pdf->useTemplate($tplId, 0, 0);

            if ($pageNo == 1) {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(14, 286, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(14, 220, 'Etat dégradé', 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(72, 286, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 286, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(81, 286, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(81, 210, 'X', 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(72, 135, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 135, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(81, 70, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(81, 135, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(85, 126, 'X', 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(105, 135, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(115, 135, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(105, 284, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(110, 284, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(115, 284, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(159, 127, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(178, 140, 'Cumulus', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(178, 247, 'Electrique', 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 1, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);
            }

            if ($pageNo == 2) {

                //Etat
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);

                if ($edl->getEntreeSonetteInterphone() == 1){
                    $pdf->RotatedText(43, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeSonetteInterphone() == 2){
                    $pdf->RotatedText(43, 229, 'BE', 90);
                } elseif ($edl->getEntreeSonetteInterphone() == 3){
                    $pdf->RotatedText(43, 229, 'EU', 90);
                } elseif ($edl->getEntreeSonetteInterphone() == 4){
                    $pdf->RotatedText(43, 229, 'ME', 90);
                } elseif ($edl->getEntreeSonetteInterphone() == 5){
                    $pdf->RotatedText(43, 229, 'ED', 90);
                }

                //Comment
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, $edl->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);

                if ($edl->getEntreePorteSerrurerie() == 1){
                    $pdf->RotatedText(53, 229, 'ETNF', 90);
                } elseif ($edl->getEntreePorteSerrurerie() == 2){
                    $pdf->RotatedText(53, 229, 'BE', 90);
                } elseif ($edl->getEntreePorteSerrurerie() == 3){
                    $pdf->RotatedText(53, 229, 'EU', 90);
                } elseif ($edl->getEntreePorteSerrurerie() == 4){
                    $pdf->RotatedText(53, 229, 'ME', 90);
                } elseif ($edl->getEntreePorteSerrurerie() == 5){
                    $pdf->RotatedText(53, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, $edl->getEntreePorteSerrurerieCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreePlafond() == 1){
                    $pdf->RotatedText(63, 229, 'ETNF', 90);
                } elseif ($edl->getEntreePlafond() == 2){
                    $pdf->RotatedText(63, 229, 'BE', 90);
                } elseif ($edl->getEntreePlafond() == 3){
                    $pdf->RotatedText(63, 229, 'EU', 90);
                } elseif ($edl->getEntreePlafond() == 4){
                    $pdf->RotatedText(63, 229, 'ME', 90);
                } elseif ($edl->getEntreePlafond() == 5){
                    $pdf->RotatedText(63, 229, 'ED', 90);
                }
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, $edl->getEntreePlafondCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreeRevetementsMuraux() == 1){
                    $pdf->RotatedText(73, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeRevetementsMuraux() == 2){
                    $pdf->RotatedText(73, 229, 'BE', 90);
                } elseif ($edl->getEntreeRevetementsMuraux() == 3){
                    $pdf->RotatedText(73, 229, 'EU', 90);
                } elseif ($edl->getEntreeRevetementsMuraux() == 4){
                    $pdf->RotatedText(73, 229, 'ME', 90);
                } elseif ($edl->getEntreeRevetementsMuraux() == 5){
                    $pdf->RotatedText(73, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(73, 214, $edl->getEntreeRevetementsMurauxCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreePlinthes() == 1){
                    $pdf->RotatedText(84, 229, 'ETNF', 90);
                } elseif ($edl->getEntreePlinthes() == 2){
                    $pdf->RotatedText(84, 229, 'BE', 90);
                } elseif ($edl->getEntreePlinthes() == 3){
                    $pdf->RotatedText(84, 229, 'EU', 90);
                } elseif ($edl->getEntreePlinthes() == 4){
                    $pdf->RotatedText(84, 229, 'ME', 90);
                } elseif ($edl->getEntreePlinthes() == 5){
                    $pdf->RotatedText(84, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $edl->getEntreePlinthesCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);

                if ($edl->getEntreeSol() == 1){
                    $pdf->RotatedText(94, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeSol() == 2){
                    $pdf->RotatedText(94, 229, 'BE', 90);
                } elseif ($edl->getEntreeSol() == 3){
                    $pdf->RotatedText(94, 229, 'EU', 90);
                } elseif ($edl->getEntreeSol() == 4){
                    $pdf->RotatedText(94, 229, 'ME', 90);
                } elseif ($edl->getEntreeSol() == 5){
                    $pdf->RotatedText(94, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(94, 214, $edl->getEntreeSol(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreeLuminaire() == 1){
                    $pdf->RotatedText(104, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeLuminaire() == 2){
                    $pdf->RotatedText(104, 229, 'BE', 90);
                } elseif ($edl->getEntreeLuminaire() == 3){
                    $pdf->RotatedText(104, 229, 'EU', 90);
                } elseif ($edl->getEntreeLuminaire() == 4){
                    $pdf->RotatedText(104, 229, 'ME', 90);
                } elseif ($edl->getEntreeLuminaire() == 5){
                    $pdf->RotatedText(104, 229, 'ED', 90);
                }


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(104, 214, $edl->getEntreeLuminaireCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreeInteruptPrise() == 1){
                    $pdf->RotatedText(115, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeInteruptPrise() == 2){
                    $pdf->RotatedText(115, 229, 'BE', 90);
                } elseif ($edl->getEntreeInteruptPrise() == 3){
                    $pdf->RotatedText(115, 229, 'EU', 90);
                } elseif ($edl->getEntreeInteruptPrise() == 4){
                    $pdf->RotatedText(115, 229, 'ME', 90);
                } elseif ($edl->getEntreeInteruptPrise() == 5){
                    $pdf->RotatedText(115, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(115, 214, $edl->getEntreeInteruptPriseCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreePlacard() == 1){
                    $pdf->RotatedText(125, 229, 'ETNF', 90);
                } elseif ($edl->getEntreePlacard() == 2){
                    $pdf->RotatedText(125, 229, 'BE', 90);
                } elseif ($edl->getEntreePlacard() == 3){
                    $pdf->RotatedText(125, 229, 'EU', 90);
                } elseif ($edl->getEntreePlacard() == 4){
                    $pdf->RotatedText(125, 229, 'ME', 90);
                } elseif ($edl->getEntreePlacard() == 5){
                    $pdf->RotatedText(125, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(125, 214, $edl->getEntreePlacard(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreeFenetre() == 1){
                    $pdf->RotatedText(137, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeFenetre() == 2){
                    $pdf->RotatedText(137, 229, 'BE', 90);
                } elseif ($edl->getEntreeFenetre() == 3){
                    $pdf->RotatedText(137, 229, 'EU', 90);
                } elseif ($edl->getEntreeFenetre() == 4){
                    $pdf->RotatedText(137, 229, 'ME', 90);
                } elseif ($edl->getEntreeFenetre() == 5){
                    $pdf->RotatedText(137, 229, 'ED', 90);
                }


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(137, 214, $edl->getEntreeFenetreCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getEntreeVolet() == 1){
                    $pdf->RotatedText(147, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeVolet() == 2){
                    $pdf->RotatedText(147, 229, 'BE', 90);
                } elseif ($edl->getEntreeVolet() == 3){
                    $pdf->RotatedText(147, 229, 'EU', 90);
                } elseif ($edl->getEntreeVolet() == 4){
                    $pdf->RotatedText(147, 229, 'ME', 90);
                } elseif ($edl->getEntreeVolet() == 5){
                    $pdf->RotatedText(147, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(147, 214, $edl->getEntreeVoletCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(157, 224, $dataEDL->getEntreeSonetteInterphone(), 90);
                if ($edl->getEntreeVolet() == 1){
                    $pdf->RotatedText(157, 229, 'ETNF', 90);
                } elseif ($edl->getEntreeVolet() == 2){
                    $pdf->RotatedText(157, 229, 'BE', 90);
                } elseif ($edl->getEntreeVolet() == 3){
                    $pdf->RotatedText(157, 229, 'EU', 90);
                } elseif ($edl->getEntreeVolet() == 4){
                    $pdf->RotatedText(157, 229, 'ME', 90);
                } elseif ($edl->getEntreeVolet() == 5){
                    $pdf->RotatedText(157, 229, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(157, 214, $edl->getEntreeVoletCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 2, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }

            // SEJOUR / CUISINE N°1

            if ($pageNo == 3) {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourFaience() == 1){
                    $pdf->RotatedText(50, 224, 'ETNF', 90);
                } elseif ($edl->getSejourFaience() == 2){
                    $pdf->RotatedText(50, 224, 'BE', 90);
                } elseif ($edl->getSejourFaience() == 3){
                    $pdf->RotatedText(50, 224, 'EU', 90);
                } elseif ($edl->getSejourFaience() == 4){
                    $pdf->RotatedText(50, 224, 'ME', 90);
                } elseif ($edl->getSejourFaience() == 5){
                    $pdf->RotatedText(50, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(50, 214, $edl->getSejourFaienceCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourPaillasse() == 1){
                    $pdf->RotatedText(60, 224, 'ETNF', 90);
                } elseif ($edl->getSejourPaillasse() == 2){
                    $pdf->RotatedText(60, 224, 'BE', 90);
                } elseif ($edl->getSejourPaillasse() == 3){
                    $pdf->RotatedText(60, 224, 'EU', 90);
                } elseif ($edl->getSejourPaillasse() == 4){
                    $pdf->RotatedText(60, 224, 'ME', 90);
                } elseif ($edl->getSejourPaillasse() == 5){
                    $pdf->RotatedText(60, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(60, 214, $edl->getSejourPaillasseCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourEvier() == 1){
                    $pdf->RotatedText(70, 224, 'ETNF', 90);
                } elseif ($edl->getSejourEvier() == 2){
                    $pdf->RotatedText(70, 224, 'BE', 90);
                } elseif ($edl->getSejourEvier() == 3){
                    $pdf->RotatedText(70, 224, 'EU', 90);
                } elseif ($edl->getSejourEvier() == 4){
                    $pdf->RotatedText(70, 224, 'ME', 90);
                } elseif ($edl->getSejourEvier() == 5){
                    $pdf->RotatedText(70, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(70, 214, $edl->getSejourEvierCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourRobinetterie() == 1){
                    $pdf->RotatedText(80, 224, 'ETNF', 90);
                } elseif ($edl->getSejourRobinetterie() == 2){
                    $pdf->RotatedText(80, 224, 'BE', 90);
                } elseif ($edl->getSejourRobinetterie() == 3){
                    $pdf->RotatedText(80, 224, 'EU', 90);
                } elseif ($edl->getSejourRobinetterie() == 4){
                    $pdf->RotatedText(80, 224, 'ME', 90);
                } elseif ($edl->getSejourRobinetterie() == 5){
                    $pdf->RotatedText(80, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(80, 214, $edl->getSejourRobinetterieCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourVmc() == 1){
                    $pdf->RotatedText(90, 224, 'ETNF', 90);
                } elseif ($edl->getSejourVmc() == 2){
                    $pdf->RotatedText(90, 224, 'BE', 90);
                } elseif ($edl->getSejourVmc() == 3){
                    $pdf->RotatedText(90, 224, 'EU', 90);
                } elseif ($edl->getSejourVmc() == 4){
                    $pdf->RotatedText(90, 224, 'ME', 90);
                } elseif ($edl->getSejourVmc() == 5){
                    $pdf->RotatedText(90, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(90, 214, $dataEDL->getSejourVmcCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourTableCuisson() == 1){
                    $pdf->RotatedText(  100, 224, 'ETNF', 90);
                } elseif ($edl->getSejourTableCuisson() == 2){
                    $pdf->RotatedText(100, 224, 'BE', 90);
                } elseif ($edl->getSejourTableCuisson() == 3){
                    $pdf->RotatedText(100, 224, 'EU', 90);
                } elseif ($edl->getSejourTableCuisson() == 4){
                    $pdf->RotatedText(100, 224, 'ME', 90);
                } elseif ($edl->getSejourTableCuisson() == 5){
                    $pdf->RotatedText(100, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(100, 214, $edl->getSejourTableCuissonCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourFrigo() == 1){
                    $pdf->RotatedText(  111, 224, 'ETNF', 90);
                } elseif ($edl->getSejourFrigo() == 2){
                    $pdf->RotatedText(111, 224, 'BE', 90);
                } elseif ($edl->getSejourFrigo() == 3){
                    $pdf->RotatedText(111, 224, 'EU', 90);
                } elseif ($edl->getSejourFrigo() == 4){
                    $pdf->RotatedText(111, 224, 'ME', 90);
                } elseif ($edl->getSejourFrigo() == 5){
                    $pdf->RotatedText(111, 224, 'ED', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(111, 214, $edl->getSejourFrigoCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourHotte() == 1){
                    $pdf->RotatedText(  121, 224, 'ETNF', 90);
                } elseif ($edl->getSejourHotte() == 2){
                    $pdf->RotatedText(121, 224, 'BE', 90);
                } elseif ($edl->getSejourHotte() == 3){
                    $pdf->RotatedText(121, 224, 'EU', 90);
                } elseif ($edl->getSejourHotte() == 4){
                    $pdf->RotatedText(121, 224, 'ME', 90);
                } elseif ($edl->getSejourHotte() == 5){
                    $pdf->RotatedText(121, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(121, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(121, 214, $dataEDL->getSejourHotteCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourRegletteLumineuse() == 1){
                    $pdf->RotatedText(  132, 224, 'ETNF', 90);
                } elseif ($edl->getSejourRegletteLumineuse() == 2){
                    $pdf->RotatedText(132, 224, 'BE', 90);
                } elseif ($edl->getSejourRegletteLumineuse() == 3){
                    $pdf->RotatedText(132, 224, 'EU', 90);
                } elseif ($edl->getSejourRegletteLumineuse() == 4){
                    $pdf->RotatedText(132, 224, 'ME', 90);
                } elseif ($edl->getSejourRegletteLumineuse() == 5){
                    $pdf->RotatedText(132, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(132, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(132, 214, $dataEDL->getSejourRegletteLumineuseCom(), 90);


                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 3, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }

            // SEJOUR / CUISINE N°2
            if ($pageNo == '4') {


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourPorteSerrurerie() == 1){
                    $pdf->RotatedText(  48, 224, 'ETNF', 90);
                } elseif ($edl->getSejourPorteSerrurerie() == 2){
                    $pdf->RotatedText(48, 224, 'BE', 90);
                } elseif ($edl->getSejourPorteSerrurerie() == 3){
                    $pdf->RotatedText(48, 224, 'EU', 90);
                } elseif ($edl->getSejourPorteSerrurerie() == 4){
                    $pdf->RotatedText(48, 224, 'ME', 90);
                } elseif ($edl->getSejourPorteSerrurerie() == 5){
                    $pdf->RotatedText(48, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(48, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(48, 214, $dataEDL->getSejourPorteSerrurerieCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourPlafond() == 1){
                    $pdf->RotatedText(  58, 224, 'ETNF', 90);
                } elseif ($edl->getSejourPlafond() == 2){
                    $pdf->RotatedText(58, 224, 'BE', 90);
                } elseif ($edl->getSejourPlafond() == 3){
                    $pdf->RotatedText(58, 224, 'EU', 90);
                } elseif ($edl->getSejourPlafond() == 4){
                    $pdf->RotatedText(58, 224, 'ME', 90);
                } elseif ($edl->getSejourPlafond() == 5){
                    $pdf->RotatedText(58, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(58, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 214, $dataEDL->getSejourPlafondCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourRevetementMuraux() == 1){
                    $pdf->RotatedText(  68, 224, 'ETNF', 90);
                } elseif ($edl->getSejourRevetementMuraux() == 2){
                    $pdf->RotatedText(68, 224, 'BE', 90);
                } elseif ($edl->getSejourRevetementMuraux() == 3){
                    $pdf->RotatedText(68, 224, 'EU', 90);
                } elseif ($edl->getSejourRevetementMuraux() == 4){
                    $pdf->RotatedText(68, 224, 'ME', 90);
                } elseif ($edl->getSejourRevetementMuraux() == 5){
                    $pdf->RotatedText(68, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(68, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(68, 214, $dataEDL->getSejourRevetementMurauxCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourPlinthes() == 1){
                    $pdf->RotatedText(  78, 224, 'ETNF', 90);
                } elseif ($edl->getSejourPlinthes() == 2){
                    $pdf->RotatedText(78, 224, 'BE', 90);
                } elseif ($edl->getSejourPlinthes() == 3){
                    $pdf->RotatedText(78, 224, 'EU', 90);
                } elseif ($edl->getSejourPlinthes() == 4){
                    $pdf->RotatedText(78, 224, 'ME', 90);
                } elseif ($edl->getSejourPlinthes() == 5){
                    $pdf->RotatedText(78, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(78, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(78, 214, $dataEDL->getSejourPlinthesCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourSol() == 1){
                    $pdf->RotatedText(  88, 224, 'ETNF', 90);
                } elseif ($edl->getSejourSol() == 2){
                    $pdf->RotatedText(88, 224, 'BE', 90);
                } elseif ($edl->getSejourSol() == 3){
                    $pdf->RotatedText(88, 224, 'EU', 90);
                } elseif ($edl->getSejourSol() == 4){
                    $pdf->RotatedText(88, 224, 'ME', 90);
                } elseif ($edl->getSejourSol() == 5){
                    $pdf->RotatedText(88, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(88, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(88, 214, $dataEDL->getSejourSolCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourLuminaire() == 1){
                    $pdf->RotatedText(  98, 224, 'ETNF', 90);
                } elseif ($edl->getSejourLuminaire() == 2){
                    $pdf->RotatedText(98, 224, 'BE', 90);
                } elseif ($edl->getSejourLuminaire() == 3){
                    $pdf->RotatedText(98, 224, 'EU', 90);
                } elseif ($edl->getSejourLuminaire() == 4){
                    $pdf->RotatedText(98, 224, 'ME', 90);
                } elseif ($edl->getSejourLuminaire() == 5){
                    $pdf->RotatedText(98, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(98, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(98, 214, $dataEDL->getSejourLuminaireCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourInteruptPrise() == 1){
                    $pdf->RotatedText(  109, 224, 'ETNF', 90);
                } elseif ($edl->getSejourInteruptPrise() == 2){
                    $pdf->RotatedText(109, 224, 'BE', 90);
                } elseif ($edl->getSejourInteruptPrise() == 3){
                    $pdf->RotatedText(109, 224, 'EU', 90);
                } elseif ($edl->getSejourInteruptPrise() == 4){
                    $pdf->RotatedText(109, 224, 'ME', 90);
                } elseif ($edl->getSejourInteruptPrise() == 5){
                    $pdf->RotatedText(109, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(109, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(109, 214, $dataEDL->getSejourInteruptPriseCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourRadiateur() == 1){
                    $pdf->RotatedText(  119, 224, 'ETNF', 90);
                } elseif ($edl->getSejourRadiateur() == 2){
                    $pdf->RotatedText(119, 224, 'BE', 90);
                } elseif ($edl->getSejourRadiateur() == 3){
                    $pdf->RotatedText(119, 224, 'EU', 90);
                } elseif ($edl->getSejourRadiateur() == 4){
                    $pdf->RotatedText(119, 224, 'ME', 90);
                } elseif ($edl->getSejourRadiateur() == 5){
                    $pdf->RotatedText(119, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(119, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(119, 214, $dataEDL->getSejourRadiateurCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourFenetre() == 1){
                    $pdf->RotatedText(  130, 224, 'ETNF', 90);
                } elseif ($edl->getSejourFenetre() == 2){
                    $pdf->RotatedText(130, 224, 'BE', 90);
                } elseif ($edl->getSejourFenetre() == 3){
                    $pdf->RotatedText(130, 224, 'EU', 90);
                } elseif ($edl->getSejourFenetre() == 4){
                    $pdf->RotatedText(130, 224, 'ME', 90);
                } elseif ($edl->getSejourFenetre() == 5){
                    $pdf->RotatedText(130, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(130, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(130, 214, $dataEDL->getSejourFenetreCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourVolet() == 1){
                    $pdf->RotatedText(  140, 224, 'ETNF', 90);
                } elseif ($edl->getSejourVolet() == 2){
                    $pdf->RotatedText(140, 224, 'BE', 90);
                } elseif ($edl->getSejourVolet() == 3){
                    $pdf->RotatedText(140, 224, 'EU', 90);
                } elseif ($edl->getSejourVolet() == 4){
                    $pdf->RotatedText(140, 224, 'ME', 90);
                } elseif ($edl->getSejourVolet() == 5){
                    $pdf->RotatedText(140, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(140, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(140, 214, $dataEDL->getSejourVoletCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourMeubleCuisineBas() == 1){
                    $pdf->RotatedText(  150, 224, 'ETNF', 90);
                } elseif ($edl->getSejourMeubleCuisineBas() == 2){
                    $pdf->RotatedText(150, 224, 'BE', 90);
                } elseif ($edl->getSejourMeubleCuisineBas() == 3){
                    $pdf->RotatedText(150, 224, 'EU', 90);
                } elseif ($edl->getSejourMeubleCuisineBas() == 4){
                    $pdf->RotatedText(150, 224, 'ME', 90);
                } elseif ($edl->getSejourMeubleCuisineBas() == 5){
                    $pdf->RotatedText(150, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(150, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(150, 214, $dataEDL->getSejourMeubleCuisineBasCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSejourMeubleCuisineHaut() == 1){
                    $pdf->RotatedText(  160, 224, 'ETNF', 90);
                } elseif ($edl->getSejourMeubleCuisineHaut() == 2){
                    $pdf->RotatedText(160, 224, 'BE', 90);
                } elseif ($edl->getSejourMeubleCuisineHaut() == 3){
                    $pdf->RotatedText(160, 224, 'EU', 90);
                } elseif ($edl->getSejourMeubleCuisineHaut() == 4){
                    $pdf->RotatedText(160, 224, 'ME', 90);
                } elseif ($edl->getSejourMeubleCuisineHaut() == 5){
                    $pdf->RotatedText(160, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(160, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(160, 214, $dataEDL->getSejourMeubleCuisineHautCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 4, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }

            // SALLE DE BAIN N°1
            if ($pageNo == '5') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbPorteSerrurerie() == 1){
                    $pdf->RotatedText(  43, 227, 'ETNF', 90);
                } elseif ($edl->getSdbPorteSerrurerie() == 2){
                    $pdf->RotatedText(43, 227, 'BE', 90);
                } elseif ($edl->getSdbPorteSerrurerie() == 3){
                    $pdf->RotatedText(43, 227, 'EU', 90);
                } elseif ($edl->getSdbPorteSerrurerie() == 4){
                    $pdf->RotatedText(43, 227, 'ME', 90);
                } elseif ($edl->getSdbPorteSerrurerie() == 5){
                    $pdf->RotatedText(43, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(43, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, $dataEDL->getSdbPorteSerrurerieCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbPlafond() == 1){
                    $pdf->RotatedText( 53, 227, 'ETNF', 90);
                } elseif ($edl->getSdbPlafond() == 2){
                    $pdf->RotatedText(53, 227, 'BE', 90);
                } elseif ($edl->getSdbPlafond() == 3){
                    $pdf->RotatedText(53, 227, 'EU', 90);
                } elseif ($edl->getSdbPlafond() == 4){
                    $pdf->RotatedText(53, 227, 'ME', 90);
                } elseif ($edl->getSdbPlafond() == 5){
                    $pdf->RotatedText(53, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(53, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, $dataEDL->getSdbPlafondCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbRevetementsMuraux() == 1){
                    $pdf->RotatedText( 63, 227, 'ETNF', 90);
                } elseif ($edl->getSdbRevetementsMuraux() == 2){
                    $pdf->RotatedText(63, 227, 'BE', 90);
                } elseif ($edl->getSdbRevetementsMuraux() == 3){
                    $pdf->RotatedText(63, 227, 'EU', 90);
                } elseif ($edl->getSdbRevetementsMuraux() == 4){
                    $pdf->RotatedText(63, 227, 'ME', 90);
                } elseif ($edl->getSdbRevetementsMuraux() == 5){
                    $pdf->RotatedText(63, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(63, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, $dataEDL->getSdbRevetementsMuraux(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbPlinthes() == 1){
                    $pdf->RotatedText( 74, 227, 'ETNF', 90);
                } elseif ($edl->getSdbPlinthes() == 2){
                    $pdf->RotatedText(74, 227, 'BE', 90);
                } elseif ($edl->getSdbPlinthes() == 3){
                    $pdf->RotatedText(74, 227, 'EU', 90);
                } elseif ($edl->getSdbPlinthes() == 4){
                    $pdf->RotatedText(74, 227, 'ME', 90);
                } elseif ($edl->getSdbPlinthes() == 5){
                    $pdf->RotatedText(74, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(74, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 214, $dataEDL->getSdbPlinthesCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbSol() == 1){
                    $pdf->RotatedText( 84, 227, 'ETNF', 90);
                } elseif ($edl->getSdbSol() == 2){
                    $pdf->RotatedText(84, 227, 'BE', 90);
                } elseif ($edl->getSdbSol() == 3){
                    $pdf->RotatedText(84, 227, 'EU', 90);
                } elseif ($edl->getSdbSol() == 4){
                    $pdf->RotatedText(84, 227, 'ME', 90);
                } elseif ($edl->getSdbSol() == 5){
                    $pdf->RotatedText(84, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(84, 227, 'NC', 90);
                }


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getSdbSol(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbLuminaire() == 1){
                    $pdf->RotatedText( 95, 227, 'ETNF', 90);
                } elseif ($edl->getSdbLuminaire() == 2){
                    $pdf->RotatedText(95, 227, 'BE', 90);
                } elseif ($edl->getSdbLuminaire() == 3){
                    $pdf->RotatedText(95, 227, 'EU', 90);
                } elseif ($edl->getSdbLuminaire() == 4){
                    $pdf->RotatedText(95, 227, 'ME', 90);
                } elseif ($edl->getSdbLuminaire() == 5){
                    $pdf->RotatedText(95, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(95, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 214, $dataEDL->getSdbLuminaireCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbInteruptPrise() == 1){
                    $pdf->RotatedText( 106, 227, 'ETNF', 90);
                } elseif ($edl->getSdbInteruptPrise() == 2){
                    $pdf->RotatedText(106, 227, 'BE', 90);
                } elseif ($edl->getSdbInteruptPrise() == 3){
                    $pdf->RotatedText(106, 227, 'EU', 90);
                } elseif ($edl->getSdbInteruptPrise() == 4){
                    $pdf->RotatedText(106, 227, 'ME', 90);
                } elseif ($edl->getSdbInteruptPrise() == 5){
                    $pdf->RotatedText(106, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(106, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getSdbInteruptPriseCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbRadiateur() == 1){
                    $pdf->RotatedText( 116, 227, 'ETNF', 90);
                } elseif ($edl->getSdbRadiateur() == 2){
                    $pdf->RotatedText(116, 227, 'BE', 90);
                } elseif ($edl->getSdbRadiateur() == 3){
                    $pdf->RotatedText(116, 227, 'EU', 90);
                } elseif ($edl->getSdbRadiateur() == 4){
                    $pdf->RotatedText(116, 227, 'ME', 90);
                } elseif ($edl->getSdbRadiateur() == 5){
                    $pdf->RotatedText(116, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(116, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getSdbRadiateurCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbPlacard() == 1){
                    $pdf->RotatedText( 127, 227, 'ETNF', 90);
                } elseif ($edl->getSdbPlacard() == 2){
                    $pdf->RotatedText(127, 227, 'BE', 90);
                } elseif ($edl->getSdbPlacard() == 3){
                    $pdf->RotatedText(127, 227, 'EU', 90);
                } elseif ($edl->getSdbPlacard() == 4){
                    $pdf->RotatedText(127, 227, 'ME', 90);
                } elseif ($edl->getSdbPlacard() == 5){
                    $pdf->RotatedText(127, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(127, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getSdbPlacardCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbFenetre() == 1){
                    $pdf->RotatedText( 138, 227, 'ETNF', 90);
                } elseif ($edl->getSdbFenetre() == 2){
                    $pdf->RotatedText(138, 227, 'BE', 90);
                } elseif ($edl->getSdbFenetre() == 3){
                    $pdf->RotatedText(138, 227, 'EU', 90);
                } elseif ($edl->getSdbFenetre() == 4){
                    $pdf->RotatedText(138, 227, 'ME', 90);
                } elseif ($edl->getSdbFenetre() == 5){
                    $pdf->RotatedText(138, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(138, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 214, $dataEDL->getSdbFenetre(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbVolet() == 1){
                    $pdf->RotatedText( 148, 227, 'ETNF', 90);
                } elseif ($edl->getSdbVolet() == 2){
                    $pdf->RotatedText(148, 227, 'BE', 90);
                } elseif ($edl->getSdbVolet() == 3){
                    $pdf->RotatedText(148, 227, 'EU', 90);
                } elseif ($edl->getSdbVolet() == 4){
                    $pdf->RotatedText(148, 227, 'ME', 90);
                } elseif ($edl->getSdbVolet() == 5){
                    $pdf->RotatedText(148, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(148, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 214, $dataEDL->getSdbVolet(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 5, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);
            }

            // SALLE DE BAIN N°2
            if ($pageNo == '6') {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbLavabo() == 1){
                    $pdf->RotatedText(  43, 224, 'ETNF', 90);
                } elseif ($edl->getSdbLavabo() == 2){
                    $pdf->RotatedText(43, 224, 'BE', 90);
                } elseif ($edl->getSdbLavabo() == 3){
                    $pdf->RotatedText(43, 224, 'EU', 90);
                } elseif ($edl->getSdbLavabo() == 4){
                    $pdf->RotatedText(43, 224, 'ME', 90);
                } elseif ($edl->getSdbLavabo() == 5){
                    $pdf->RotatedText(43, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(43, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, $dataEDL->getSdbLavaboCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbRobinetterieLavabo() == 1){
                    $pdf->RotatedText( 53, 224, 'ETNF', 90);
                } elseif ($edl->getSdbRobinetterieLavabo() == 2){
                    $pdf->RotatedText(53, 224, 'BE', 90);
                } elseif ($edl->getSdbRobinetterieLavabo() == 3){
                    $pdf->RotatedText(53, 224, 'EU', 90);
                } elseif ($edl->getSdbRobinetterieLavabo() == 4){
                    $pdf->RotatedText(53, 224, 'ME', 90);
                } elseif ($edl->getSdbRobinetterieLavabo() == 5){
                    $pdf->RotatedText(53, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(53, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, $dataEDL->getSdbRobinetterieLavaboCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 224, "NC" , 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, "pas de bidet, a champ a virer", 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 224, "NC", 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 214, "pas de bidet, a champ a virer", 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);                if ($edl->getSdbDouche() == 1){
                    $pdf->RotatedText( 84, 224, 'ETNF', 90);
                } elseif ($edl->getSdbDouche() == 2){
                    $pdf->RotatedText(84, 224, 'BE', 90);
                } elseif ($edl->getSdbDouche() == 3){
                    $pdf->RotatedText(84, 224, 'EU', 90);
                } elseif ($edl->getSdbDouche() == 4){
                    $pdf->RotatedText(84, 224, 'ME', 90);
                } elseif ($edl->getSdbDouche() == 5){
                    $pdf->RotatedText(84, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(84, 224, 'NC', 90);
                }


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getSdbDoucheCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbRobinetterieDouche() == 1){
                    $pdf->RotatedText( 95, 224, 'ETNF', 90);
                } elseif ($edl->getSdbRobinetterieDouche() == 2){
                    $pdf->RotatedText(95, 224, 'BE', 90);
                } elseif ($edl->getSdbRobinetterieDouche() == 3){
                    $pdf->RotatedText(95, 224, 'EU', 90);
                } elseif ($edl->getSdbRobinetterieDouche() == 4){
                    $pdf->RotatedText(95, 224, 'ME', 90);
                } elseif ($edl->getSdbRobinetterieDouche() == 5){
                    $pdf->RotatedText(95, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(95, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 214, $dataEDL->getSdbRobinetterieDoucheCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbParoiDouche() == 1){
                    $pdf->RotatedText( 106, 224, 'ETNF', 90);
                } elseif ($edl->getSdbParoiDouche() == 2){
                    $pdf->RotatedText(106, 224, 'BE', 90);
                } elseif ($edl->getSdbParoiDouche() == 3){
                    $pdf->RotatedText(106, 224, 'EU', 90);
                } elseif ($edl->getSdbParoiDouche() == 4){
                    $pdf->RotatedText(106, 224, 'ME', 90);
                } elseif ($edl->getSdbParoiDouche() == 5){
                    $pdf->RotatedText(106, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(106, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getSdbParoiDoucheCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbBaignoire() == 1){
                    $pdf->RotatedText( 116, 224, 'ETNF', 90);
                } elseif ($edl->getSdbBaignoire() == 2){
                    $pdf->RotatedText(116, 224, 'BE', 90);
                } elseif ($edl->getSdbBaignoire() == 3){
                    $pdf->RotatedText(116, 224, 'EU', 90);
                } elseif ($edl->getSdbBaignoire() == 4){
                    $pdf->RotatedText(116, 224, 'ME', 90);
                } elseif ($edl->getSdbBaignoire() == 5){
                    $pdf->RotatedText(116, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(116, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getSdbBaignoireCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbRobinetterieBaignoire() == 1){
                    $pdf->RotatedText( 127, 224, 'ETNF', 90);
                } elseif ($edl->getSdbRobinetterieBaignoire() == 2){
                    $pdf->RotatedText(127, 224, 'BE', 90);
                } elseif ($edl->getSdbRobinetterieBaignoire() == 3){
                    $pdf->RotatedText(127, 224, 'EU', 90);
                } elseif ($edl->getSdbRobinetterieBaignoire() == 4){
                    $pdf->RotatedText(127, 224, 'ME', 90);
                } elseif ($edl->getSdbRobinetterieBaignoire() == 5){
                    $pdf->RotatedText(127, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(127, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getSdbRobinetterieBaignoire(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbFaience() == 1){
                    $pdf->RotatedText( 138, 224, 'ETNF', 90);
                } elseif ($edl->getSdbFaience() == 2){
                    $pdf->RotatedText(138, 224, 'BE', 90);
                } elseif ($edl->getSdbFaience() == 3){
                    $pdf->RotatedText(138, 224, 'EU', 90);
                } elseif ($edl->getSdbFaience() == 4){
                    $pdf->RotatedText(138, 224, 'ME', 90);
                } elseif ($edl->getSdbFaience() == 5){
                    $pdf->RotatedText(138, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(138, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 214, $dataEDL->getSdbFaienceCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getSdbJoints() == 1){
                    $pdf->RotatedText( 148, 224, 'ETNF', 90);
                } elseif ($edl->getSdbJoints() == 2){
                    $pdf->RotatedText(148, 224, 'BE', 90);
                } elseif ($edl->getSdbJoints() == 3){
                    $pdf->RotatedText(148, 224, 'EU', 90);
                } elseif ($edl->getSdbJoints() == 4){
                    $pdf->RotatedText(148, 224, 'ME', 90);
                } elseif ($edl->getSdbJoints() == 5){
                    $pdf->RotatedText(148, 224, 'ED', 90);
                }else{
                    $pdf->RotatedText(148, 224, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 214, $dataEDL->getSdbJointsCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 6, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }

            // COULOIR
            if ($pageNo == '7') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirPorteSerrurerie() == 1){
                    $pdf->RotatedText( 41, 227, 'ETNF', 90);
                } elseif ($edl->getCouloirPorteSerrurerie() == 2){
                    $pdf->RotatedText(41, 227, 'BE', 90);
                } elseif ($edl->getCouloirPorteSerrurerie() == 3){
                    $pdf->RotatedText(41, 227, 'EU', 90);
                } elseif ($edl->getCouloirPorteSerrurerie() == 4){
                    $pdf->RotatedText(41, 227, 'ME', 90);
                } elseif ($edl->getCouloirPorteSerrurerie() == 5){
                    $pdf->RotatedText(41, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(41, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 214, $dataEDL->getCouloirPorteSerrurerieCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirPlafond() == 1){
                    $pdf->RotatedText( 51, 227, 'ETNF', 90);
                } elseif ($edl->getCouloirPlafond() == 2){
                    $pdf->RotatedText(51, 227, 'BE', 90);
                } elseif ($edl->getCouloirPlafond() == 3){
                    $pdf->RotatedText(51, 227, 'EU', 90);
                } elseif ($edl->getCouloirPlafond() == 4){
                    $pdf->RotatedText(51, 227, 'ME', 90);
                } elseif ($edl->getCouloirPlafond() == 5){
                    $pdf->RotatedText(51, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(51, 227, 'NC', 90);
                }
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 214, $dataEDL->getCouloirPlafondCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirRevetementMuraux() == 1){
                    $pdf->RotatedText( 61, 227, 'ETNF', 90);
                } elseif ($edl->getCouloirRevetementMuraux() == 2){
                    $pdf->RotatedText(61, 227, 'BE', 90);
                } elseif ($edl->getCouloirRevetementMuraux() == 3){
                    $pdf->RotatedText(61, 227, 'EU', 90);
                } elseif ($edl->getCouloirRevetementMuraux() == 4){
                    $pdf->RotatedText(61, 227, 'ME', 90);
                } elseif ($edl->getCouloirRevetementMuraux() == 5){
                    $pdf->RotatedText(61, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(61, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 227, $dataEDL->getCouloirRevetementMurauxCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirPlinthes() == 1){
                    $pdf->RotatedText( 74, 227, 'ETNF', 90);
                } elseif ($edl->getCouloirPlinthes() == 2){
                    $pdf->RotatedText(74, 227, 'BE', 90);
                } elseif ($edl->getCouloirPlinthes() == 3){
                    $pdf->RotatedText(74, 227, 'EU', 90);
                } elseif ($edl->getCouloirPlinthes() == 4){
                    $pdf->RotatedText(74, 227, 'ME', 90);
                } elseif ($edl->getCouloirPlinthes() == 5){
                    $pdf->RotatedText(74, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(74, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 214, $dataEDL->getCouloirPlinthesCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirSol() == 1){
                    $pdf->RotatedText( 84, 227, 'ETNF', 90);
                } elseif ($edl->getCouloirSol() == 2){
                    $pdf->RotatedText(84, 227, 'BE', 90);
                } elseif ($edl->getCouloirSol() == 3){
                    $pdf->RotatedText(84, 227, 'EU', 90);
                } elseif ($edl->getCouloirSol() == 4){
                    $pdf->RotatedText(84, 227, 'ME', 90);
                } elseif ($edl->getCouloirSol() == 5){
                    $pdf->RotatedText(84, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(84, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getCouloirSolCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirLuminaire() == 1){
                    $pdf->RotatedText( 95, 227, 'ETNF', 90);
                } elseif ($edl->getCouloirLuminaire() == 2){
                    $pdf->RotatedText(95, 227, 'BE', 90);
                } elseif ($edl->getCouloirLuminaire() == 3){
                    $pdf->RotatedText(95, 227, 'EU', 90);
                } elseif ($edl->getCouloirLuminaire() == 4){
                    $pdf->RotatedText(95, 227, 'ME', 90);
                } elseif ($edl->getCouloirLuminaire() == 5){
                    $pdf->RotatedText(95, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(95, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 214, $dataEDL->getCouloirLuminaireCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirInteruptPrise() == 1){
                    $pdf->RotatedText(106 , 227, 'ETNF', 90);
                } elseif ($edl->getCouloirInteruptPrise() == 2){
                    $pdf->RotatedText(106, 227, 'BE', 90);
                } elseif ($edl->getCouloirInteruptPrise() == 3){
                    $pdf->RotatedText(106, 227, 'EU', 90);
                } elseif ($edl->getCouloirInteruptPrise() == 4){
                    $pdf->RotatedText(106, 227, 'ME', 90);
                } elseif ($edl->getCouloirInteruptPrise() == 5){
                    $pdf->RotatedText(106, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(106, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getCouloirInteruptPriseCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirFenetre() == 1){
                    $pdf->RotatedText(116 , 227, 'ETNF', 90);
                } elseif ($edl->getCouloirFenetre() == 2){
                    $pdf->RotatedText(116, 227, 'BE', 90);
                } elseif ($edl->getCouloirFenetre() == 3){
                    $pdf->RotatedText(116, 227, 'EU', 90);
                } elseif ($edl->getCouloirFenetre() == 4){
                    $pdf->RotatedText(116, 227, 'ME', 90);
                } elseif ($edl->getCouloirFenetre() == 5){
                    $pdf->RotatedText(116, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(116, 227, 'NC', 90);
                }


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getCouloirFenetreCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getCouloirVolet() == 1){
                    $pdf->RotatedText(127 , 227, 'ETNF', 90);
                } elseif ($edl->getCouloirVolet() == 2){
                    $pdf->RotatedText(127, 227, 'BE', 90);
                } elseif ($edl->getCouloirVolet() == 3){
                    $pdf->RotatedText(127, 227, 'EU', 90);
                } elseif ($edl->getCouloirVolet() == 4){
                    $pdf->RotatedText(127, 227, 'ME', 90);
                } elseif ($edl->getCouloirVolet() == 5){
                    $pdf->RotatedText(127, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(127, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getCouloirVoletCom(), 90);



                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 7, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);
            }

            // WC
            if ($pageNo == '8') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcPorteSerrurerie() == 1){
                    $pdf->RotatedText(43 , 227, 'ETNF', 90);
                } elseif ($edl->getWcPorteSerrurerie() == 2){
                    $pdf->RotatedText(43, 227, 'BE', 90);
                } elseif ($edl->getWcPorteSerrurerie() == 3){
                    $pdf->RotatedText(43, 227, 'EU', 90);
                } elseif ($edl->getWcPorteSerrurerie() == 4){
                    $pdf->RotatedText(43, 227, 'ME', 90);
                } elseif ($edl->getWcPorteSerrurerie() == 5){
                    $pdf->RotatedText(43, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(43, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, $dataEDL->getWcPorteSerrurerieCom(), 90);


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcPlafond() == 1){
                    $pdf->RotatedText(53 , 227, 'ETNF', 90);
                } elseif ($edl->getWcPlafond() == 2){
                    $pdf->RotatedText(53, 227, 'BE', 90);
                } elseif ($edl->getWcPlafond() == 3){
                    $pdf->RotatedText(53, 227, 'EU', 90);
                } elseif ($edl->getWcPlafond() == 4){
                    $pdf->RotatedText(53, 227, 'ME', 90);
                } elseif ($edl->getWcPlafond() == 5){
                    $pdf->RotatedText(53, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(53, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, $dataEDL->getWcPlafondCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcRevetementMuraux() == 1){
                    $pdf->RotatedText(63 , 227, 'ETNF', 90);
                } elseif ($edl->getWcRevetementMuraux() == 2){
                    $pdf->RotatedText(63, 227, 'BE', 90);
                } elseif ($edl->getWcRevetementMuraux() == 3){
                    $pdf->RotatedText(63, 227, 'EU', 90);
                } elseif ($edl->getWcRevetementMuraux() == 4){
                    $pdf->RotatedText(63, 227, 'ME', 90);
                } elseif ($edl->getWcRevetementMuraux() == 5){
                    $pdf->RotatedText(63, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(63, 227, 'NC', 90);
                }
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, $dataEDL->getWcRevetementMurauxCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcPlinthes() == 1){
                    $pdf->RotatedText(76 , 227, 'ETNF', 90);
                } elseif ($edl->getWcPlinthes() == 2){
                    $pdf->RotatedText(76, 227, 'BE', 90);
                } elseif ($edl->getWcPlinthes() == 3){
                    $pdf->RotatedText(76, 227, 'EU', 90);
                } elseif ($edl->getWcPlinthes() == 4){
                    $pdf->RotatedText(76, 227, 'ME', 90);
                } elseif ($edl->getWcPlinthes() == 5){
                    $pdf->RotatedText(76, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(76, 227, 'NC', 90);
                }
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 214, $dataEDL->getWcPlinthesCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcSol() == 1){
                    $pdf->RotatedText(86 , 227, 'ETNF', 90);
                } elseif ($edl->getWcSol() == 2){
                    $pdf->RotatedText(86, 227, 'BE', 90);
                } elseif ($edl->getWcSol() == 3){
                    $pdf->RotatedText(86, 227, 'EU', 90);
                } elseif ($edl->getWcSol() == 4){
                    $pdf->RotatedText(86, 227, 'ME', 90);
                } elseif ($edl->getWcSol() == 5){
                    $pdf->RotatedText(86, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(86, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(86, 214, $dataEDL->getWcSolCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcLuminaire() == 1){
                    $pdf->RotatedText(96 , 227, 'ETNF', 90);
                } elseif ($edl->getWcLuminaire() == 2){
                    $pdf->RotatedText(96, 227, 'BE', 90);
                } elseif ($edl->getWcLuminaire() == 3){
                    $pdf->RotatedText(96, 227, 'EU', 90);
                } elseif ($edl->getWcLuminaire() == 4){
                    $pdf->RotatedText(96, 227, 'ME', 90);
                } elseif ($edl->getWcLuminaire() == 5){
                    $pdf->RotatedText(96, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(96, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(96, 214, $dataEDL->getWcLuminaire(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcInteruptPrise() == 1){
                    $pdf->RotatedText(106 , 227, 'ETNF', 90);
                } elseif ($edl->getWcInteruptPrise() == 2){
                    $pdf->RotatedText(106, 227, 'BE', 90);
                } elseif ($edl->getWcInteruptPrise() == 3){
                    $pdf->RotatedText(106, 227, 'EU', 90);
                } elseif ($edl->getWcInteruptPrise() == 4){
                    $pdf->RotatedText(106, 227, 'ME', 90);
                } elseif ($edl->getWcInteruptPrise() == 5){
                    $pdf->RotatedText(106, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(106, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getWcInteruptPriseCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcRadiateur() == 1){
                    $pdf->RotatedText(116 , 227, 'ETNF', 90);
                } elseif ($edl->getWcRadiateur() == 2){
                    $pdf->RotatedText(116, 227, 'BE', 90);
                } elseif ($edl->getWcRadiateur() == 3){
                    $pdf->RotatedText(116, 227, 'EU', 90);
                } elseif ($edl->getWcRadiateur() == 4){
                    $pdf->RotatedText(116, 227, 'ME', 90);
                } elseif ($edl->getWcRadiateur() == 5){
                    $pdf->RotatedText(116, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(116, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getWcRadiateurCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcRadiateur() == 1){
                    $pdf->RotatedText(127 , 227, 'ETNF', 90);
                } elseif ($edl->getWcRadiateur() == 2){
                    $pdf->RotatedText(127, 227, 'BE', 90);
                } elseif ($edl->getWcRadiateur() == 3){
                    $pdf->RotatedText(127, 227, 'EU', 90);
                } elseif ($edl->getWcRadiateur() == 4){
                    $pdf->RotatedText(127, 227, 'ME', 90);
                } elseif ($edl->getWcRadiateur() == 5){
                    $pdf->RotatedText(127, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(127, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getWcRadiateurCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcCuvetteMecanisme() == 1){
                    $pdf->RotatedText(138 , 227, 'ETNF', 90);
                } elseif ($edl->getWcCuvetteMecanisme() == 2){
                    $pdf->RotatedText(138, 227, 'BE', 90);
                } elseif ($edl->getWcCuvetteMecanisme() == 3){
                    $pdf->RotatedText(138, 227, 'EU', 90);
                } elseif ($edl->getWcCuvetteMecanisme() == 4){
                    $pdf->RotatedText(138, 227, 'ME', 90);
                } elseif ($edl->getWcCuvetteMecanisme() == 5){
                    $pdf->RotatedText(138, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(138, 227, 'NC', 90);
                }


                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 214, $dataEDL->getWcCuvetteMecanismeCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcAbattant() == 1){
                    $pdf->RotatedText(148 , 227, 'ETNF', 90);
                } elseif ($edl->getWcAbattant() == 2){
                    $pdf->RotatedText(148, 227, 'BE', 90);
                } elseif ($edl->getWcAbattant() == 3){
                    $pdf->RotatedText(148, 227, 'EU', 90);
                } elseif ($edl->getWcAbattant() == 4){
                    $pdf->RotatedText(148, 227, 'ME', 90);
                } elseif ($edl->getWcAbattant() == 5){
                    $pdf->RotatedText(148, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(148, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 214, $dataEDL->getWcAbattant(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcFenetre() == 1){
                    $pdf->RotatedText(158 , 227, 'ETNF', 90);
                } elseif ($edl->getWcFenetre() == 2){
                    $pdf->RotatedText(158, 227, 'BE', 90);
                } elseif ($edl->getWcFenetre() == 3){
                    $pdf->RotatedText(158, 227, 'EU', 90);
                } elseif ($edl->getWcFenetre() == 4){
                    $pdf->RotatedText(158, 227, 'ME', 90);
                } elseif ($edl->getWcFenetre() == 5){
                    $pdf->RotatedText(158, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(158, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(158, 214, $dataEDL->getWcFenetreCom(), 90);




                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcVolet() == 1){
                    $pdf->RotatedText(168 , 227, 'ETNF', 90);
                } elseif ($edl->getWcVolet() == 2){
                    $pdf->RotatedText(168, 227, 'BE', 90);
                } elseif ($edl->getWcVolet() == 3){
                    $pdf->RotatedText(168, 227, 'EU', 90);
                } elseif ($edl->getWcVolet() == 4){
                    $pdf->RotatedText(168, 227, 'ME', 90);
                } elseif ($edl->getWcVolet() == 5){
                    $pdf->RotatedText(168, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(168, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(168, 214, $dataEDL->getWcVoletCom(), 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                if ($edl->getWcFaiences() == 1){
                    $pdf->RotatedText(178 , 227, 'ETNF', 90);
                } elseif ($edl->getWcFaiences() == 2){
                    $pdf->RotatedText(178, 227, 'BE', 90);
                } elseif ($edl->getWcFaiences() == 3){
                    $pdf->RotatedText(178, 227, 'EU', 90);
                } elseif ($edl->getWcFaiences() == 4){
                    $pdf->RotatedText(178, 227, 'ME', 90);
                } elseif ($edl->getWcFaiences() == 5){
                    $pdf->RotatedText(178, 227, 'ED', 90);
                }else{
                    $pdf->RotatedText(178, 227, 'NC', 90);
                }

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(178, 214, $dataEDL->getWcFaiencesCom(), 90);



                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 8, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '9') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 9, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '10') {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 10, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '11') {
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(41, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(51, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(61, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(74, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(95, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(106, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(116, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(127, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(138, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 11, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '12') {
                // 1 ere ligne
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 35, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 103, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 166, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 233, $dataEDL->getEntreeSonetteInterphone(), 90);

                // 2 eme ligne
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 35, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 103, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 166, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 233, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 135, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 200, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 135, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 200, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 65, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 65, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(17, 116, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(17, 90, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(17, 45, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(22, 130, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(92, 243, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(97, 237, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(117, 275, $dataEDL->getEntreeSonetteInterphone(), 90);


                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 12, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signature, 130, 296, 45, 16, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(148, 296, 'Je reconnais avoir', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(152, 296, 'recu un exemplaire', 90);

            }
        }

        $fileName = $dataEDL->getEntreeSonetteInterphone() . '-etat-des-lieux.pdf';
        $pdf->Output('F', $this->params->get('kernel.project_dir') . '/public/pdf/etat-des-lieux/' . $fileName);
    }
}


