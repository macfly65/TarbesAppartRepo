<?php

namespace App\Controller;

use App\Entity\Edl;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\EdlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DisponibiliteFormType;
use App\Form\EdlFormType;
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;



class AdminAppartController extends AbstractController
{
    private $entityManager;
    private $security;


    public function __construct(Security $security, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->security = $security;
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
    public function disponibilite(AppartementRepository $appartRepo, Request $request, \Swift_Mailer $mailer)
    {
        $searchResidence = $searchAppart = 0;
        $appartDispo = [];
        $allAppart = $appartRepo->findAll();

        // Récupération des appartement disponibles
        $dateDay = date("Y-m-d", strtotime("+1 day"));
        foreach ($allAppart as $appartement){
            $dispoAppart = $appartement->getDisponibilite()->format('Y-m-d');
            if($dispoAppart <= $dateDay){
                array_push($appartDispo, $appartement);
            }
        }
        $form = $this->createForm(DisponibiliteFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && isset($_POST['appart'])) {
            $apparts = $_POST['appart'];

            $dispos = $form->getData();

            $message = (new \Swift_Message('disponibilités'))
                // On attribue l'expéditeur
                ->setFrom($dispos['Email'])

                // On attribue le destinataire
                ->setTo('florent.bvs@gmail.com')

                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'layout_emails/disponibilite.html.twig', compact('dispos','apparts')),
                    'text/html'
                );
             $mailer->send($message);

            $this->addFlash(
                'notice',
                'Votre message a été transmis !'
            );            return $this->redirectToRoute('admin_appart_dispo');
        }


        return $this->render('admin/admin_appart/disponibilite.html.twig', [
            'appartDispo' => $appartDispo,
            'disposForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/appart/edl/{id}", name="etat_des_lieux")
     */
    public function etatDesLieux($id, AppartementRepository $apparts, EdlRepository $edl, Request $request) {
        $appart = $apparts->find($id);
        $user = $this->security->getUser();


        // Gestion des filtres
        $edl = new Edl();
        $form = $this->createForm(EdlFormType::class, $edl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('admin/admin_appart/etatDesLieux.html.twig', [
            'idAppart' => $id,
            'appart' => $appart,
            'form'=> $form->createView(),
            'user' => $user
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
