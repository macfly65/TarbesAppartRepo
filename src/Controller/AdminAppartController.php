<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Appartement;
use App\Entity\Residence;
use App\Entity\AppartementMedias;
use App\Repository\AppartementRepository;
use App\Repository\ResidenceRepository;
use App\Repository\AppartementMediasRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Service\FlashNotificationAjax;
use Symfony\Component\HttpFoundation\JsonResponse;


class AdminAppartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/appart", name="admin_appart")
     */
    public function index(AppartementRepository $appart, Request $request)
    {
        $appartList = $appart->findAll();

        // Gestion des filtres
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($_GET['numAppart'] != "" || $_GET['residence'] != "0") {
                $searchResidence = $searchAppart = 0;
           $searchAppart = $_GET['numAppart'];
           $searchResidence = $_GET['residence'];
           $appartList = $appart->findAppartAdmin($searchAppart,$searchResidence);
             }
        }

        return $this->render('admin/admin_appart/index.html.twig', [
            'appartList' => $appartList,
            'form'=> $form->createView()
        ]);
    }
    
    /**
     * @Route("/admin/appart/create", name="create_appart")
     */
    public function form(Request $request) {
        $appartement = new Appartement;
        $form = $this->createFormBuilder($appartement)
                ->add('residence', EntityType::class, [
                    'class' => Residence::class,
                    'choice_label' => 'nom'
                ])
                ->add('numero')
                ->add('loyerEtudiant')
                ->add('loyerHotelSemaine')
                ->add('loyerHotelJour')
                ->add('type')
                ->add('surface')
                ->add('etage')
                ->add('exposition')
                ->add('statut', ChoiceType::class, [
                    'choices' => [
                        'En ligne' => 1,
                        'Hors ligne' => 2,
                    ],
                ])
                ->add('disponibilite')
                ->add('dateDisponibilite')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($appartement);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_appart');
        }

        return $this->render('admin/admin_appart/create.html.twig', [
                    'formAppart' => $form->createView(),
                    'idCatalogue' => '',
                    'idAppart' => ''
        ]);
    }
    
    /**
     * @Route("/admin/appart/medias/{id}", name="edit_medias_appart")
     */
    public function addMedias($id, AppartementMediasRepository $medias, AppartementRepository $appartement, Request $request) {
        $appartement = $appartement->find($id);
        $medias = $medias->findBy(['appartement' => $id],['ordre' => 'ASC']);


        
        $appartementMedias = new AppartementMedias;
        $form = $this->createFormBuilder($appartementMedias)
                ->add('nomFichier', FileType::class, [
                    'data_class' => null,
                    'required' => false,
                    'multiple' => true
                ])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $files est un tableau de fichier     
            $files = $form['nomFichier']->getData();
            
            //on boucle dessus, on vas crer une ligne en bdd pour chaque fichier. 
            foreach ($files as $file) {
                $extension = $file->guessExtension();
                $fileName = $file->getFilename();
                $img = $fileName . '.' . $extension;

                $appartementMedias = new AppartementMedias;
                $appartementMedias->setNomFichier($img);
                $appartementMedias->setAppartement($appartement);
                $img2 = $appartementMedias->getNomFichier();
                
                $file->move($this->getParameter('upload_directory'), $img);

                $this->entityManager->persist($appartementMedias);
                $this->entityManager->flush();
            }
            return $this->redirectToRoute('edit_medias_appart', array('id' => $id ));
        }

        return $this->render('admin/admin_appart/addMedias.html.twig', [
                    'appartMedias' => $form->createView(),
                    'idAppart' => $id,
                    'medias'=> $medias
        ]);
    }
    
    /**
     * @Route("/admin/appart/edit/{id}", name="edit_appart")
     */
    public function editAppart($id, AppartementRepository $apparts, Request $request) {
        $appart = $apparts->find($id);
        $form = $this->createFormBuilder($appart)
                ->add('residence', EntityType::class, [
                    'class' => Residence::class,
                    'choice_label' => 'nom'
                ])
                ->add('numero')
                ->add('loyerEtudiant')
                ->add('loyerHotelSemaine')
                ->add('loyerHotelJour')
                ->add('type')
                ->add('surface')
                ->add('etage')
                ->add('exposition')
                ->add('statut', ChoiceType::class, [
                    'choices' => [
                        'En ligne' => 1,
                        'Hors ligne' => 2,
                    ],
                ])
                ->add('disponibilite')
                ->add('dateDisponibilite')
                ->getForm();
        
          $form->handleRequest($request);
        
          if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($appart);
            $this->entityManager->flush();
        }

        return $this->render('admin/admin_appart/create.html.twig', [
                    'formAppart' => $form->createView(),
                    'idAppart' => $id,
                    'appart' => $appart,
        ]);
    }
    
    /**
     * @Route("/ajaxUpdateAlt", name="axUpdateAlt", options={"expose"=true})
    */     
    public function ajaxUpdateAlt(AppartementMediasRepository $appartementMedias, FlashNotificationAjax $flash) { 
        //enregistrement des Alt des img
        if(isset($_POST['postAlt'])){
           $media = $appartementMedias->find($_POST['idImg']);
           $media->setAlt($_POST['valAlt']);
        
           $this->entityManager->persist($media);
           $this->entityManager->flush();
        }
          return new JsonResponse([
            'status' => true,
            'flashMessage' => $flash->notification('success', 'Enregistrement avec succès'),
        ]);  
   }

    /**
     * @Route("/admin/medias/delete/{id}", name="delete_one_media_appart")
     */
    public function deleteOneMediaAppart($id, AppartementMediasRepository $medias) {
        $medias = $medias->find($id);
                
        $this->entityManager->remove($medias);
        $this->entityManager->flush();
        $appartement = $medias->getAppartement(); 
        $appartementId = $appartement->getId(); 
        
        return $this->redirectToRoute('edit_medias_appart', array('id' => $appartementId ));
    }
    /**
     * @Route("/admin/appart/disponibilite", name="admin_appart_dispo")
     */
    public function disponibilite(AppartementRepository $appart, Request $request)
    {
        $searchResidence = $searchAppart = 0;
        $appartDispo = [];
        $allAppart = $appart->findAll();


        // Gestion des filtres
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($_GET['numAppart'] != "" || $_GET['residence'] != "0") {

                //on vide le tableau de tout les apparts
                $allAppart = [];

                $searchResidence = $searchAppart = 0;
                $searchAppart = $_GET['numAppart'];
                $searchResidence = $_GET['residence'];
                $searchAppart = $appart->findAppartAdmin($searchAppart,$searchResidence);

                foreach ($searchAppart as $appartFinded){
                    array_push($allAppart, $appartFinded);
                }
            }
        }

        // Récupération des appartement disponibles
        $dateDay = date("Y-m-d", strtotime("+1 day"));
        foreach ($allAppart as $appartement){
            $dispoAppart = $appartement->getDisponibilite()->format('Y-m-d');
            if($dispoAppart <= $dateDay){
                array_push($appartDispo, $appartement);
            }
        }

        return $this->render('admin/admin_appart/disponibilite.html.twig', [
            'appartDispo' => $appartDispo,
            'form'=> $form->createView()
        ]);
    }


    /**
     * @Route("/admin/ajaxSortMediasAppart", name="axSortMediasAppart")
     * @param Request $request
     */
    public function axSortMediasAppart (AppartementMediasRepository $appartementMedias, FlashNotificationAjax $flash, Request $request){
        if (isset($_POST['updateOrderMediasAppart'])) {
           // var_dump($_POST['positions']);
            foreach ($_POST['positions'] as $position) {
                $index = $position[0];
                $newPosition = $position[1];

                var_dump($index);
                var_dump($newPosition);
                $appartMedia = $appartementMedias->find($index);
                $appartMedia->setOrdre($newPosition);

                $this->entityManager->persist($appartMedia);
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
     * @Route("/admin/ajaxUpdateAltAppart", name="axUpdateAltAppart")
     * @param Request $request
     */
    public function axUpdateAltAppart (AppartementMediasRepository $appartementMedias, FlashNotificationAjax $flash, Request $request){
        //enregistrement des Alt des img
        if(isset($_POST['postAlt'])){
            $media = $appartementMedias->find($_POST['idImg']);
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
