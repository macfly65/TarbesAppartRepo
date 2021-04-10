<?php
namespace App\Service;

use App\Entity\ContactHistorique;
use Twig\Environment;
use App\Entity\User;

class MailerService {
    
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     * @var Environment
     */
    private $renderer;
    
     public function __construct(\Swift_Mailer $mailer, Environment $renderer) {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }
    
    /**
     * Envoi d'un email
     * @param object $formData    object contenant les donées soumise dans le formulaire
     */
    public function send($formData)
    {
      //envoi du mail
      $message = (new \Swift_Message('Tarbes Appart'))
              ->setFrom('florent.bvs@gmail.com')
              ->setTo('florent.bvs@gmail.com');      
      $img_bg1 = $message->embed(\Swift_Image::fromPath('medias_front/mail/bg_1.jpg'));
      $message->setBody($this->renderer->render('layout_emails/contact.html.twig', [
          'img_bg1'  => $img_bg1,
          'formData' => $formData]), 'text/html');
        var_dump($this->mailer->send($message));

    }
    
     /**
     * Envoi d'un email lors de la perte d'un mot de passe
     * @param object $formData    object contenant les donées soumise dans le formulaire
     */
    public function sendForgotMdp($userId,$userMail)
    {     
        $safetyCode = $userId*42;
        
      //envoi du mail  
      $message = (new \Swift_Message('Tarbes Appart'))
              ->setFrom('florent.bvs@gmail.com')
              ->setTo($userMail);      
      $img_bg1 = $message->embed(\Swift_Image::fromPath('medias_front/mail/bg_1.jpg'));
      $message->setBody($this->renderer->render('layout_emails/mdpOublie.html.twig', [
          'img_bg1'  => $img_bg1,
          'safetyCode' => $safetyCode]), 'text/html');
      
      $this->mailer->send($message);
    }



    /**
     * Envoi d'un email au client lors d'une validation ou annulation d'un réservation
     * @param object $formData object contenant les donées soumise dans le formulaire
     */
    public function sendNotificationReservation($locataire, $user)
    {
        //envoi du mail
        $message = (new \Swift_Message('Tarbes Appart'))
            ->setFrom('florent.bvs@gmail.com')
            ->setTo($locataire->getEmail());

        $img_bg1 = $message->embed(\Swift_Image::fromPath('medias_front/mail/bg_1.jpg'));

        if($locataire->getStatut() == 1){ //résa validé
            $message->setBody($this->renderer->render('layout_emails/resaValid.html.twig', ['img_bg1'  => $img_bg1,'locataire' => $locataire, 'user' => $user]), 'text/html');
        }else{
            $message->setBody($this->renderer->render('layout_emails/resaAnnule.html.twig', ['img_bg1'  => $img_bg1,'locataire' => $locataire, 'user' => $user]), 'text/html');
        }
        $this->mailer->send($message);
    }
} 