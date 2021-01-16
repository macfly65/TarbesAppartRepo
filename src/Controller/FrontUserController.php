<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use App\Form\MdpOublieType;
use App\Form\NewCredentialType;
use App\Entity\User;
use App\Repository\UserRepository;



class FrontUserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/nouveaux_compte", name="newAccount")
     */
    public function index(UserPasswordEncoderInterface $encoder, Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mdp = $user->getPassword();
            $mdpVerify = $user->getPasswordVerify();
          if($mdpVerify == $mdp){  
            $encoded = $encoder->encodePassword($user, $mdp);
            $user->setPassword($encoded);
            $user->setPasswordVerify(NULL);
            $user->setRoles("ROLE_USER");
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash(
             'notice',
             'Votre compte a bien été créer, vous pouvez maintenant vous connecter.'
           );
            
            return $this->redirectToRoute('app_login');
            }else{
              $this->addFlash(
                'error',
                "La confirmation de votre mot de passe n'est pas correcte."
              );
            }
        }      
      
        return $this->render('front_user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
       /**
     * @Route("/forgotCredential", name="mdpOublie")
     */
    public function mdpOublie(Request $request, UserRepository $users, MailerService $mailerService)
    {
        $user = new User();
        $form = $this->createForm(MdpOublieType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mailUser = $data->getEmail();
            $user = $users->findBy(['email' => $mailUser]);
            if ($user == null){
                $this->addFlash(
                  'error',
                  "Cet email n'existe pas dans notre base de donées."
                );
               return $this->redirectToRoute('mdpOublie');
            }else{
            $userId = $user[0]->getId();
            $userMail = $user[0]->getEmail();

            //envoi du mail
                $mailerService->sendForgotMdp($userId,$userMail);
              $this->addFlash(
                  'notice',
                  "Nous vous avons envoyé un e-mail pour créer un nouveau mot de passe."
                );
            }
        }      
       return $this->render('front_user/mdpOublie.html.twig',[
            'form' => $form->createView(),
       ]);
    }


       /**
     * @Route("/newCredential/{id}", name="newCredential")
     */
    public function newCredential(UserPasswordEncoderInterface $encoder,Request $request, $id, UserRepository $users)
    {
       $idDecoded = $id/42;
       $user = $users->findBy(['id' => $idDecoded]);
       $user = $user[0];
       $form = $this->createForm(NewCredentialType::class, $user);
       $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp = $user->getPassword();
            $mdpVerify = $user->getPasswordVerify();
          if($mdpVerify == $mdp){  
            $encoded = $encoder->encodePassword($user, $mdp);
            $user->setPassword($encoded);
            $user->setPasswordVerify(NULL);
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash(
             'notice',
             'Votre mot de passe a bien été modifié, vous pouvez maintenant vous connecter.'
           );
            return $this->redirectToRoute('app_login');
          }else{
              $this->addFlash(
                'error',
                "La confirmation de votre mot de passe n'est pas correcte."
              );
          }
    }
    
          return $this->render('front_user/newCredential.html.twig',[
            'form' => $form->createView(),
       ]);
  
}
}