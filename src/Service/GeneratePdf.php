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

    public function generateEDL($dataEDL){
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
                $pdf->RotatedText(14, 220, $dataEDL->getEntreeSonetteInterphone(), 90);

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
                $pdf->RotatedText(158, 125, 'X', 90);



                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(105, 284, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(110, 284, 'X', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(115, 284, 'X', 90);

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
                $pdf->RotatedText(43, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                //Comment
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, 'Commentaire', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, 'Commentaire', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, 'Commentaire', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(73, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(73, 214, 'Commentaire', 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(84, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(94, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(94, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(104, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(104, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(115, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(115, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(125, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(125, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(137, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(137, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(147, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(147, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(157, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(157, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 2, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == 3) {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(50, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(50, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(60, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(60, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(70, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(70, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(80, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(80, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(90, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(90, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(100, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(100, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(111, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(111, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(121, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(121, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(132, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(132, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 3, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '4') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(48, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(48, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(58, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(68, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(68, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(78, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(78, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(88, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(88, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(98, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(98, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(109, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(109, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(119, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(119, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(130, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(130, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(140, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(140, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(150, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(150, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(160, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(160, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 4, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '5') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

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
                $pdf->RotatedText(194, 27, 5, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);
            }
            if ($pageNo == '6') {

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
                $pdf->RotatedText(194, 27, 6, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);

            }
            if ($pageNo == '7') {

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
                $pdf->RotatedText(137, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(137, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                //pager
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 27, 7, 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(194, 22, 12, 90);

                $pdf->RotatedImage($signatureTampon, 198, 290, 8, 8, 90);
            }
            if ($pageNo == '8') {

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(43, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(53, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(63, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(76, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(86, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(86, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(96, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(96, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

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

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(158, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(158, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(168, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(168, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(178, 224, $dataEDL->getEntreeSonetteInterphone(), 90);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->RotatedText(178, 214, $dataEDL->getEntreeSonetteInterphoneCom(), 90);

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


