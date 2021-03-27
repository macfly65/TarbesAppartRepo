<?php


namespace App\Service;


use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Notification
{
    private $mailer;
    private $templating;

    public function __construct( \Swift_Mailer $mailer, \Twig\Environment $templating )
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }


    // rest of class will use these services as if injected directly

    public function sendNotifSms($senderName, $numeroReceiver)
    {
        $token ="396c4ccd18224d3d89c829bbe02c7738";
        //Send an SMS
        $mj = new \Mailjet\Client($token,
            NULL, true,
            ['url' => "api.mailjet.com", 'version' => 'v4', 'call' => false]
        );
        $body = [
            'Text' => "Voici notre nouveau service !",
            'To' => $numeroReceiver,
            'From' => $senderName,
        ];
        $response = $mj->post(Resources::$SmsSend, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }


    public function sendNotifMail($pt)
    {
        $mailer = $this->mailer;
        //$prestaMail = $pt->getPrestataire()->getemail();
        $objet = "Demande d'intervention pour l'appartement n°". $pt->getAppartement()->getNumero();

        $message = (new \Swift_Message($objet))
            // On attribue l'expéditeur
            ->setFrom('florent.bvs@gmail.com')
            // On attribue le destinataire
            //->setTo($prestaMail)
            ->setTo('florent.bvs@gmail.com')

            // On crée le texte avec la vue
            ->setBody(
                $this->templating->render(
                    'layout_emails/pt.html.twig', compact('pt')),
                'text/html'
            );


        $mailer->send($message);
    }
}


