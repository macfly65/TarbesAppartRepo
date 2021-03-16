<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ActualiteCategs;
use App\Entity\ActualiteMedias;
use App\Entity\Actualite;
use App\Repository\ActualiteCategsRepository;
use App\Repository\ActualiteRepository;
use App\Repository\ActualiteMediasRepository;
use App\Form\ActualiteType;
use App\Form\ActualiteMediasType;
use App\Form\ActualiteCategsType;
use App\Service\FlashNotificationAjax;



class AdminActualiteController extends AbstractController {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/actualite/{page}", name="admin_actualite")
     */
    public function index($page = 0, actualiteCategsRepository $actualiteCategRepository, actualiteRepository $actualiteRepository, Request $request) {
        $actualiteCateg = $actualiteCategRepository->findBy(array(), array('ordre' => 'ASC'));

            //PAGINATION : si $page = 0, $firstResult == 0, si $page = 1, $firstResult == 10 ...
        $page == 0 ? $firstResult = 0 : $firstResult = $page*10;
        $actualites = $actualiteRepository->findactualite($firstResult);
        
        $categ = new actualiteCategs;
        $form = $this->createForm(ActualiteCategsType::class, $categ);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categ);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_actualite');
        }

        return $this->render('admin/admin_actualite/index.html.twig', [
                    'actualiteCateg' => $actualiteCateg,
                    'actualites' => $actualites,
                    'formCateg' => $form->createView(),
                    'page' => $page
        ]);
    }

    /**
     * @Route("/admin/actualite/new/create", name="create_actualite")
     */
    public function form(Request $request) {
        $actualite = new Actualite;
        $form = $this->createFormBuilder($actualite);
        $form = $this->createForm(ActualiteType::class, $actualite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($actualite);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_actualite');
        }

        return $this->render('admin/admin_actualite/create.html.twig', [
                    'formActualite' => $form->createView(),
                    'idActualite' => ''
        ]);
    }

    /**
     * @Route("/admin/actualite/edit/{id}", name="edit_actualite")
     */
    public function showOnePromo($id, actualiteRepository $actualites, Request $request) {
        $actualite = $actualites->find($id);
        $form = $this->createForm(actualiteType::class, $actualite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($actualite);
            $this->entityManager->flush();
        }
        return $this->render('admin/admin_actualite/create.html.twig', [
                    'formActualite' => $form->createView(),
                    'idActualite' => $id,
                    'actualite' => $actualite
        ]);
    }

    /**
     * @Route("/admin/actualite/medias/{id}", name="edit_medias_actualite")
     */
    public function addMedias($id, ActualiteMediasRepository $actualiteMedias, ActualiteRepository $actualites, Request $request) {
        $actualite = $actualites->find($id);
        $medias = $actualiteMedias->findBy(['Actualite' => $id], ['ordre' => 'ASC']);

        $actualiteMedia = new actualiteMedias;
        $form = $this->createForm(actualiteMediasType::class, $actualiteMedia);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $files est un tableau de fichier     
            $files = $form['nomFichier']->getData();
            
            //on boucle dessus, on vas crer une ligne en bdd pour chaque fichier. 
            foreach ($files as $file) {
                $img = $file->getClientOriginalName();
                
                $actualiteMediasMultiple = new actualiteMedias;
                $actualiteMediasMultiple->setNomFichier($img);
                $actualiteMediasMultiple->setActualite($actualite);

                $file->move($this->getParameter('upload_actualite'), $img);

                $this->entityManager->persist($actualiteMediasMultiple);
                $this->entityManager->flush();
            }
            return $this->redirectToRoute('edit_medias_actualite', array('id' => $id ));
        }

        return $this->render('admin/admin_actualite/addMedias.html.twig', [
                    'actualiteMedias' => $form->createView(),
                    'idactualite' => $id,
                    'medias' => $medias,
                    'actualite' => $actualite
        ]);
    }

    /**
     * @Route("/admin/actualiteCateg/delete/{id}", name="delete_categ_actualite")
     */
    public function deleteCateg($id, actualiteCategsRepository $categ) {
        $categ = $categ->find($id);
        $this->entityManager->remove($categ);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_actualite');
    }

    /**
     * @Route("/admin/actualite/delete/{id}", name="delete_actualite")
     */
    public function deleteactualite($id, actualiteRepository $actualite, actualiteMediasRepository $medias) {
        $actualite = $actualite->find($id);
        $medias = $medias-> findBy(['Actualite' => $id ]);
        
       foreach($medias as $media){
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        }
        $this->entityManager->remove($actualite);        
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_actualite');
    }

       /**
     * @Route("/admin/actualite/medias/delete/{id}", name="delete_one_actu_media")
     */
    public function deleteOneMedia($id, ActualiteMediasRepository $medias) {
        $media = $medias->find($id);
                
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        $actualite = $media->getActualite(); 
        $actualiteId = $actualite->getId(); 
        
        return $this->redirectToRoute('edit_medias_actualite', array('id' => $actualiteId ));
    }
    

    /** 
     * @Route("/admin/ajaxSortCategorieActu", name="axSortCategorieActu")
     * @param Request $request
    */  
    public function axSortCategorieActu (ActualiteCategsRepository $actualiteCategs, FlashNotificationAjax $flash, Request $request){
        if (isset($_POST['updateOrderActu'])) {
            foreach ($_POST['positions'] as $position) {
                $index = $position[0];
                $newPosition = $position[1];

                $actualiteCateg = $actualiteCategs->find($index);
                $actualiteCateg->setOrdre($newPosition);

                $this->entityManager->persist($actualiteCateg);
                $this->entityManager->flush();
            }
        
            return new JsonResponse([
                'status' => true,
                'flashMessage' => $flash->notification('success', 'Enregistrement avec succès'),
            ]); 
        }
          return new JsonResponse([
            'status' => false,
        ]);    
    }
    
    /** 
     * @Route("/admin/ajaxSortMediasActu", name="axSortMediasActu")
     * @param Request $request
    */  
    public function axSortMediasActu (ActualiteMediasRepository $actualiteMedias, FlashNotificationAjax $flash, Request $request){
           if (isset($_POST['updateOrderMediasActu'])) {
               //var_dump($_POST['positions']);
            foreach ($_POST['positions'] as $position) {
                $index = $position[0];
                $newPosition = $position[1];

                $actualiteMedia = $actualiteMedias->find($index);
                $actualiteMedia->setOrdre($newPosition);

                $this->entityManager->persist($actualiteMedia);
                $this->entityManager->flush();
            }
        
            return new JsonResponse([
                'status' => true,
                'flashMessage' => $flash->notification('success', 'Enregistrement avec succès'),
            ]); 
        }
          return new JsonResponse([
            'status' => false,
        ]);    
    }
 
    /** 
     * @Route("/admin/ajaxUpdateAltActu", name="axUpdateAltActu")
     * @param Request $request
    */  
    public function axUpdateAltActu (ActualiteMediasRepository $actualiteMedias, FlashNotificationAjax $flash, Request $request){
           //enregistrement des Alt des img
        if(isset($_POST['postAlt'])){
           $media = $actualiteMedias->find($_POST['idImg']);
           $media->setAlt($_POST['valAlt']);
        
           $this->entityManager->persist($media);
           $this->entityManager->flush();
        
            return new JsonResponse([
                'status' => true,
                'flashMessage' => $flash->notification('success', 'Enregistrement avec succès'),
            ]); 
        }
          return new JsonResponse([
            'status' => false,
        ]);    
    }  
}


