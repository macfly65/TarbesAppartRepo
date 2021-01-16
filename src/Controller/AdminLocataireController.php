<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Locataire;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Appartements;
use App\Repository\LocataireRepository;
use App\Form\LocataireType;
use Symfony\Component\HttpFoundation\Request;

class AdminLocataireController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/admin/locataire", name="admin_locataire")
     */
    public function index(LocataireRepository $locataire)
    {
        $locataires = $locataire->findAll();
        
        return $this->render('admin/admin_locataire/index.html.twig', [
            'locataires' => $locataires,
        ]);
    }
    
    /**
     * @Route("/admin/fiche/locataire/{id}", name="admin_locataire_fiche")
     */
    public function ficheLocataire(LocataireRepository $locataire, $id, Request $request)
    {
        $locataires = $locataire->find($id);
        
        
        $form = $this->createForm(LocataireType::class, $locataires);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         $this->entityManager->persist($locataires);
         $this->entityManager->flush();
         
          $this->addFlash(
          'notice',
          'Les modifications ont bien été enregistré.'
        );
        }      
        
        return $this->render('admin/admin_locataire/locataire_fiche.html.twig', [
            'locataires' => $locataires,
            'form' => $form->createView()
        ]);
    }
}
