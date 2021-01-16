<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ActualiteCategs;
use App\Entity\ActualiteMedias;
use App\Entity\Actualite;
use App\Entity\CommentaireActualite;
use App\Repository\CommentaireActualiteRepository;
use App\Repository\ActualiteCategsRepository;
use App\Repository\ActualiteRepository;
use App\Repository\ActualiteMediasRepository;
use App\Form\CommentaireActualiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;



class FrontActualiteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    
    /**
     * @Route("/actualite/{id}", name="front_actualite")
     */
    public function index($id = null, actualiteRepository $actualites ,actualiteCategsRepository $actualiteCateges, actualiteMediasRepository $medias)
    {
        $categories = $actualiteCateges->findBy(array(), array('ordre' => 'ASC'));
        
        if(isset($id) != null){
           $actualites = $actualites->findBy(['categorie' => $id]);
        }else{
           $actualites = $actualites->findAll();
        }
        
        
        
        return $this->render('front_actualite/index.html.twig', [
            'controller_name' => 'FrontactualiteController',
            'categories' => $categories,
            'actualites' => $actualites
                ]);
    }
    
    /**
     * @Route("/actualite/fiche/{id}", name="fiche_actualite")
     */
    public function showOnePromo($id, actualiteRepository $actualites, actualiteMediasRepository $medias, CommentaireActualiteRepository $comments, Request $request) {
        $actualite = $actualites->find($id);
        $media = $medias->findBy(['Actualite' => $id]);
        $allActu = $actualites->findAll(); 
        
        $allComment = $comments->findAll(); 
        $commentaires = $comments->findBy(['actualite' => $id]);
        $commentaire = new CommentaireActualite;
        $formCommentaire = $this->createForm(CommentaireActualiteType::class, $commentaire);        
        $formCommentaire->handleRequest($request);

            
        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $commentaire->setActualite($actualite);
            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();

            return $this->redirectToRoute('fiche_actualite', array('id' => $id ));
        }
        
        
        return $this->render('front_actualite/fiche.html.twig', [
            "actualite" => $actualite,
            "media" => $media,
            "formCommentaire" => $formCommentaire->createView(),
            "commentaires" => $commentaires,
            "allActu" => $allActu,
            "allComment" => $allComment
        ]);
    }
}
