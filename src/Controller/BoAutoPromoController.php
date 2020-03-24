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

class BoAutoPromoController extends AbstractController
{
    
    private $entityManager;
    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/admin/autopromo", name="bo_auto_promo")
     */
    public function index()
    {
        
        
        return $this->render('admin/autoPromo/index.html.twig', [
            'controller_name' => 'BoAutoPromoController',
        ]);
    }
    
     /**
     * @Route("/admin/autopromo/create", name="create_auto_promo")
     */
    public function create(Request $request){
        
            $promo = new AutoPromo;
            
          $form = $this->createFormBuilder($promo)
                        ->add('titre', TextType::class, [
                            'attr' => [
                                'placeholder' => "Titre de l'autopromo",
                                'class' => 'form-control'
                            ]
                        ])
                        ->add('periode')    
                        ->add('date_d')    
                        ->add('date_f')    
                        ->add('url')    
                        ->add('statut')    
                        ->add('image')  
                        ->add('save', SubmitType::class, [
                            'label' => 'Enregistrer'
                        ])  
                        ->getForm(); 
          
          $form->handleRequest($request);
          
          if($form->isSubmitted() && $form->isValid()){
//             $promo->setDateD(new \DateTime());
             
             $this->entityManager->persist($promo);
             $this->entityManager->flush();
             
             return $this->redirectToRoute('auto_promo');
             
          }
          
        
//                var_dump($request);
        
    return $this->render('admin/autopromo/create.html.twig',[
        'formPromo' => $form->createView()
    ]);
    }
    
    
    
}
