<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CatalogueCategs;
use App\Entity\CatalogueMedias;
use App\Entity\Catalogue;
use App\Repository\CatalogueCategsRepository;
use App\Repository\CatalogueRepository;
use App\Repository\CatalogueMediasRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdminCatalogueController extends AbstractController {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/catalogue/index/{page}", name="admin_catalogue")
     */
    public function index($page = null, CatalogueCategsRepository $catalogueCategRepository, CatalogueRepository $catalogueRepository, Request $request) {
        $catalogueCateg = $catalogueCategRepository->findBy(array(), array('ordre' => 'ASC'));
        
        //PAGINATION : si $page = 0, $firstResult == 0, si $page = 1, $firstResult == 10 ...
        $page == 0 ? $firstResult = 0 : $firstResult = $page*10;
        $catalogues = $catalogueRepository->findCatalogue($firstResult);
       
        
        if (isset($_POST['update'])) {
            foreach ($_POST['positions'] as $position) {
                $index = $position[0];
                $newPosition = $position[1];

                $catalogueCateg = $catalogueCategRepository->find($index);
                $catalogueCateg->setOrdre($newPosition);

                $this->entityManager->persist($catalogueCateg);
                $this->entityManager->flush();
            }
        }

        $categ = new CatalogueCategs;
        $form = $this->createFormBuilder($categ)
                ->add('nom')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categ);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_catalogue');
        }

        return $this->render('admin/admin_catalogue/index.html.twig', [
                    'controller_name' => 'AdminCatalogueController',
                    'catalogueCateg' => $catalogueCateg,
                    'catalogues' => $catalogues,
                    'formCateg' => $form->createView(),
                    'page' => $page
        ]);
    }

    /**
     * @Route("/admin/catalogue/create", name="create_catalogue")
     */
    public function form(Request $request) {
        $catalogue = new Catalogue;
        $form = $this->createFormBuilder($catalogue)
                ->add('reference')
                ->add('nom')
                ->add('categorieId', EntityType::class, [
                    'class' => CatalogueCategs::class,
                    'choice_label' => 'nom'
                ])
                ->add('date')
                ->add('conditionnement')
                ->add('ingredients')
                ->add('isOnline', ChoiceType::class, [
                    'choices' => [
                        'En ligne' => 1,
                        'Hors ligne' => 2,
                    ],
                ])
                ->add('description')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($catalogue);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_catalogue');
        }

        return $this->render('admin/admin_catalogue/create.html.twig', [
                    'formCatalogue' => $form->createView(),
                    'idCatalogue' => ''
        ]);
    }

    /**
     * @Route("/admin/catalogue/edit/{id}", name="edit_catalogue")
     */
    public function showOnePromo($id, CatalogueCategsRepository $catalogue, Request $request) {
        $catalogue = new Catalogue;
        $form = $this->createFormBuilder($catalogue)
                ->add('reference')
                ->add('nom')
                ->add('categorieId', EntityType::class, [
                    'class' => CatalogueCategs::class,
                    'choice_label' => 'nom'
                ])
                ->add('date')
                ->add('conditionnement')
                ->add('ingredients')
                ->add('isOnline', ChoiceType::class, [
                    'choices' => [
                        'En ligne' => 1,
                        'Hors ligne' => 2,
                    ],
                ])
                ->add('description')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($catalogue);
            $this->entityManager->flush();
        }

        return $this->render('admin/admin_catalogue/create.html.twig', [
                    'formCatalogue' => $form->createView(),
                    'idCatalogue' => $id
        ]);
    }

    /**
     * @Route("/admin/catalogue/medias/{id}", name="edit_medias_catalogue")
     */
    public function addMedias($id, CatalogueMediasRepository $catalogueMedias, CatalogueRepository $catalogue, Request $request) {
        $catalogue = $catalogue->find($id);
        $medias = $catalogueMedias->findBy(['catalogueId' => $id]);


        
        $catalogueMedias = new CatalogueMedias;
        $form = $this->createFormBuilder($catalogueMedias)
                ->add('nom_fichier', FileType::class, [
                    'data_class' => null,
                    'required' => false,
                    'multiple' => true
                ])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $files est un tableau de fichier     
            $files = $form['nom_fichier']->getData();

            //on boucle dessus, on vas crer une ligne en bdd pour chaque fichier. 
            foreach ($files as $file) {
                $extension = $file->guessExtension();
                $fileName = $file->getFilename();
                $img = $fileName . '.' . $extension;

                $catalogueMediasMultiple = new CatalogueMedias;
                $catalogueMediasMultiple->setNomFichier($img);
                $catalogueMediasMultiple->setCatalogueId($catalogue);
                $img2 = $catalogueMediasMultiple->getNomFichier();

                $file->move($this->getParameter('upload_directory'), $img);

                $this->entityManager->persist($catalogueMediasMultiple);
                $this->entityManager->flush();
//                
//                $img = $file->getClientOriginalName();
//                
//                $catalogueMediasMultiple = new CatalogueMedias;
//                $catalogueMediasMultiple->setNomFichier($img);
//                $catalogueMediasMultiple->setCatalogueId($catalogue);
//
//                $file->move($this->getParameter('upload_directory'), $img);
//
//                $this->entityManager->persist($catalogueMediasMultiple);
//                $this->entityManager->flush();
            }
            return $this->redirectToRoute('edit_medias_catalogue', array('id' => $id ));
        }

        return $this->render('admin/admin_catalogue/addMedias.html.twig', [
                    'catalogueMedias' => $form->createView(),
                    'idCatalogue' => $id,
                    'medias'=> $medias
        ]);
    }

    /**
     * @Route("/admin/catalogueCateg/delete/{id}", name="delete_categ_catalogue")
     */
    public function deleteCateg($id, CatalogueCategsRepository $categ) {
        $categ = $categ->find($id);
        $this->entityManager->remove($categ);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_catalogue');
    }

    /**
     * @Route("/admin/catalogue/delete/{id}", name="delete_catalogue")
     */
    public function deleteCatalogue($id, CatalogueRepository $catalogue, CatalogueMediasRepository $medias) {
        $catalogue = $catalogue->find($id);
        $medias = $medias-> findBy(['catalogueId' => $id ]);
        
       foreach($medias as $media){
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        }
        $this->entityManager->remove($catalogue);        
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_catalogue');
    }

       /**
     * @Route("/admin/catalogue/medias/delete/{id}", name="delete_one_media")
     */
    public function deleteOneMedia($id, CatalogueMediasRepository $medias) {
        $medias = $medias->find($id);
                
        $this->entityManager->remove($medias);
        $this->entityManager->flush();
        $catalogue = $medias->getCatalogueId(); 
        $catalogueId = $catalogue->getId(); 
        
        return $this->redirectToRoute('edit_medias_catalogue', array('id' => $catalogueId ));
    }
}
