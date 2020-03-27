<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AutoPromo;
use App\Repository\AutoPromoRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class BoAutoPromoController extends AbstractController
{
    
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/admin/autopromo", name="bo_auto_promo")
     */
    public function index(AutoPromoRepository $autoPromoRepository)
    {
        $autoPromo = $autoPromoRepository->findAll();
        
        return $this->render('admin/autoPromo/index.html.twig', [
            'controller_name' => 'BoAutoPromoController',
            'autoPromoList' => $autoPromo
        ]);
    }
    
     /**
     * @Route("/admin/autopromo/create", name="create_auto_promo")
     */
    public function form(Request $request){

          $promo = new AutoPromo;
          $form = $this->createFormBuilder($promo)
                        ->add('titre')
                        ->add('zone')
                        ->add('periode')    
                        ->add('date_d')    
                        ->add('date_f')    
                        ->add('url')    
                        ->add('cible')    
                        ->add('statut')    
                        ->add('zone')    
                        ->add('image', FileType::class)  
                        ->getForm(); 
          
          $form->handleRequest($request);
          
          if($form->isSubmitted() && $form->isValid()){
//             $promo->setDateD(new \DateTime());
              
              //gestion de l'upload de l'image
              $file = $promo->getImage();
              $fileName = md5(uniqid()).'.'.$file->guessExtension();
              $file->move($this->getParameter('upload_directory'), $fileName);
              $promo->setImage($fileName);
              $file = $promo->getImage(); 
                          
              
             $this->entityManager->persist($promo);
             $this->entityManager->flush();
             
             return $this->redirectToRoute('bo_auto_promo');
          }
        
    return $this->render('admin/autopromo/create.html.twig',[
        'formPromo' => $form->createView(),
        'image' => $promo->getImage()
    ]);
    }
    
    
     /**
     * @Route("/admin/autopromo/{id}", name="edit_auto_promo")
     */
    public function showOnePromo($id , AutoPromoRepository $autoPromo, Request $request){
            $promo = $autoPromo->find($id);
            $form = $this->createFormBuilder($promo)
                        ->add('titre')
                        ->add('periode')    
                        ->add('date_d')    
                        ->add('date_f')    
                        ->add('url')    
                        ->add('cible')    
                        ->add('statut')    
                        ->add('image')
                        ->add('zone')  
                        ->add('image', FileType::class, array('data_class' => null))  
                        ->getForm(); 
            
           $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){  
                
              //gestion de l'upload de l'image
              $file = $promo->getImage();
              $fileName = md5(uniqid()).'.'.$file->guessExtension();
              $file->move($this->getParameter('upload_directory'), $fileName);
              $promo->setImage($fileName);
                
             $this->entityManager->persist($promo);
             $this->entityManager->flush();
          }
          
     return $this->render('admin/autopromo/create.html.twig',[
        'formPromo' => $form->createView(),
        'image' => $promo->getImage()
    ]);   
    }
    
        /**
     * @Route("/admin/autopromo/delete/{id}", name="delete_promo")
     */
    public function deletePromo($id , AutoPromoRepository $promo){
            $promo = $promo->find($id);
             $this->entityManager->remove($promo);
             $this->entityManager->flush();
             
            return $this->redirectToRoute('bo_auto_promo');
    } 
    }
