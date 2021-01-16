<?php // 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminUserController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(UserRepository $userRepository)
    {
        $userRepository = $userRepository->findAll();

        return $this->render('admin/admin_user/index.html.twig', [
            'controller_name' => 'AdminUserController',
            'userList' => $userRepository
        ]);
    }
    
      
     /**
     * @Route("/admin/user/create", name="create_user")
     */
    public function form(Request $request, UserPasswordEncoderInterface $encoder){

          $user = new User;
          $form = $this->createFormBuilder($user)
                        ->add('nom')
                        ->add('prenom')
                        ->add('email')    
                        ->add('password')    
                        ->add('image', FileType::class, array('data_class' => null))  
                        ->getForm(); 
          
          $form->handleRequest($request);
          
          if($form->isSubmitted() && $form->isValid()){
             
              //gestion du role 
              if(isset($_POST['ROLE']) && $_POST['ROLE'] == 1){
                $user->setRoles("ROLE_ADMIN");
              }elseif($_POST['ROLE'] == 2){
               $user->setRoles("ROLE_SUPER_ADMIN");  
              }

              //on encode le mot de passe
              $originPassword = $user->getPassword();
              $encoded = $encoder->encodePassword($user, $originPassword);
              $user->setPassword($encoded);
              
              //gestion de l'upload de l'image
              $file = $user->getImage();
              $fileName = md5(uniqid()).'.'.$file->guessExtension();
              $file->move($this->getParameter('upload_directory'), $fileName);
              $user->setImage($fileName);
              $file = $user->getImage(); 
              
             $this->entityManager->persist($user);
             $this->entityManager->flush();
             
             return $this->redirectToRoute('admin_user');
          }
        
    return $this->render('admin/admin_user/create.html.twig',[
        'formUser' => $form->createView(),
        'image' => $user->getImage()
    ]);
    }
    
    /**
     * @Route("/admin/user/{id}", name="edit_user")
     */
    public function editUser($id , UserRepository $user, Request $request, UserPasswordEncoderInterface $encoder){
            $user = $user->find($id);
            $form = $this->createFormBuilder($user)
                        ->add('nom')
                        ->add('prenom')
                        ->add('email')    
                        ->add('password')    
                        ->add('image', FileType::class, array('data_class' => null))  
                        ->getForm(); 

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){  

             //gestion du role 
              if(isset($_POST['ROLE']) && $_POST['ROLE'] == 1){
                $user->setRoles("ROLE_ADMIN");
              }elseif($_POST['ROLE'] == 2){
               $user->setRoles("ROLE_SUPER_ADMIN");  
              }
                
              $originPassword = $user->getPassword();
              $encoded = $encoder->encodePassword($user, $originPassword);
              $user->setPassword($encoded);
              
                
              //gestion de l'upload de l'image
              $file = $user->getImage();
              $fileName = md5(uniqid()).'.'.$file->guessExtension();
              $file->move($this->getParameter('upload_directory'), $fileName);
              $user->setImage($fileName);
                
             $this->entityManager->persist($user);
             $this->entityManager->flush();
          }
          
     return $this->render('admin/admin_user/create.html.twig',[
        'formUser' => $form->createView(),
        'image' => $user->getImage()
    ]);   
    }
    
            /**
     * @Route("/admin/user/delete/{id}", name="delete_user")
     */
    public function deletePromo($id , UserRepository $user){
            $user = $user->find($id);
             $this->entityManager->remove($user);
             $this->entityManager->flush();
             
            return $this->redirectToRoute('admin_user');
    }
    
}
