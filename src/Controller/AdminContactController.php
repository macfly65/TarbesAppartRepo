<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactAdressesType;
use App\Entity\ContactAdresses;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContactHistoriqueRepository;


class AdminContactController extends AbstractController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function index(Request $request, ContactHistoriqueRepository $contacts)
    {
        return $this->render('admin/admin_contact/index.html.twig', [
            'contacts' => $contacts->findAll(),
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
