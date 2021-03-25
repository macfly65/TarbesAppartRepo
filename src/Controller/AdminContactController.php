<?php

namespace App\Controller;

use App\Form\DisponibiliteFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RespContactType;
use App\Entity\ContactAdresses;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContactHistoriqueRepository;


class AdminContactController extends AbstractController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function index(Request $request, ContactHistoriqueRepository $contactRepo)
    {
        return $this->render('admin/admin_contact/index.html.twig', [
            'contacts' => $contactRepo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/contactResp/{id}", name="contactResp")
     */
    public function contactresp(Request $request, \Swift_Mailer $mailer, ContactHistoriqueRepository $contactRepo, $id)
    {
        $form = $this->createForm(RespContactType::class);
        $form->handleRequest($request);
        $contacts = $contactRepo->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $response = $form->getData();

            $message = (new \Swift_Message('Réponse a votre message'))
                // On attribue l'expéditeur
                ->setFrom($contacts->getContFrom())

                // On attribue le destinataire
                ->setTo('florent.bvs@gmail.com')

                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'layout_emails/contactResp.html.twig', compact('contacts','response')),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash(
                'notice',
                'Votre message a été transmis !'
            );
            return $this->redirectToRoute('contactResp', array('id' => $id ));
        }
        return $this->render('admin/admin_contact/response.html.twig', [
            'contactForm' => $form->createView(),
            'contacts' => $contacts,
            'response' => $form->getData(),

        ]);
    }
//    /**
//     * @Route("/admin/contact/delete/{id}", name="delete_contact")
//     */
//    public function deleteOneMedia($id, ContactAdressesRepository $contacts) {
//        $contact = $contacts->find($id);
//        $entityManager = $this->getDoctrine()->getManager();
//        
//           try {
//                $entityManager->remove($contact);
//                $entityManager->flush();
//                $this->addFlash('success', 'L\'adresse a été suprimé');
//            } catch (Exception $e) {
//                $this->addFlash('danger', 'L\'adresse n\'a pas été suprimé');
//            }
//        return $this->redirectToRoute('admin_contact');
//    }
//    
    
}
