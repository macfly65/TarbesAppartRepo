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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AdminAutoPromoController extends AbstractController {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/autopromo", name="bo_auto_promo")
     */
    public function index(AutoPromoRepository $autoPromoRepository) {
        $autoPromo = $autoPromoRepository->findBy(array(), array('ordre' => 'ASC'));

        if (isset($_POST['update'])) {

            foreach ($_POST['positions'] as $position) {
                $index = $position[0];
                $newPosition = $position[1];
                $autoPromo = $autoPromoRepository->find($index);
                $autoPromo->setOrdre($newPosition);

                $this->entityManager->persist($autoPromo);
                $this->entityManager->flush();
            }
        }

        return $this->render('admin/autoPromo/index.html.twig', [
                    'controller_name' => 'BoAutoPromoController',
                    'autoPromoList' => $autoPromo
        ]);
    }

    /**
     * @Route("/admin/autopromo/create", name="create_auto_promo")
     */
    public function form(Request $request) {
        $promo = new AutoPromo;
        $form = $this->createFormBuilder($promo)
                ->add('zone', ChoiceType::class, [
                    'choices' => [
                        'Full page (1920 x 960 px)' => 1,
                        'Secondaire (1200 x 500 px)' => 2,
                    ],
                ])
                ->add('titre')
                ->add('sousTitre')
                ->add('url')
                ->add('cible', CheckboxType::class, [
                    'label' => 'ouvrir dans une nouvelle fenêtre ?',
                    'required' => false,
                ])
                ->add('statut', ChoiceType::class, [
                    'choices' => [
                        'En ligne' => 1,
                        'Hors ligne' => 2,
                    ],
                ])
                ->add('image', FileType::class, array('data_class' => null, 'required' => false))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//             $promo->setDateD(new \DateTime());
            //gestion de l'upload de l'image
            $file = $promo->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $promo->setImage($fileName);
//            $file = $promo->getImage();

            $this->entityManager->persist($promo);
            $this->entityManager->flush();

            return $this->redirectToRoute('bo_auto_promo');
        }

        return $this->render('admin/autopromo/create.html.twig', [
                    'formPromo' => $form->createView(),
                    'image' => $promo->getImage()
        ]);
    }

    /**
     * @Route("/admin/autopromo/{id}", name="edit_auto_promo")
     */
    public function showOnePromo($id, AutoPromoRepository $autoPromo, Request $request) {
        $promo = $autoPromo->find($id);
        $promoImage = $promo->getImage();

        $form = $this->createFormBuilder($promo)
                ->add('zone', ChoiceType::class, [
                    'choices' => [
                        'Full page (1920 x 960 px)' => 1,
                        'Secondaire (1200 x 500 px)' => 2,
                    ],
                ])
                ->add('titre')
                ->add('sousTitre')
                ->add('url')
                ->add('cible', CheckboxType::class, [
                    'label' => 'ouvrir dans une nouvelle fenêtre ?',
                    'required' => false,
                ])
                ->add('statut', ChoiceType::class, [
                    'choices' => [
                        'En ligne' => 1,
                        'Hors ligne' => 2,
                    ],
                ])
                ->add('image', FileType::class, array('data_class' => null, 'required' => false))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($promo->getImage() != NULL) {
                //gestion de l'upload de l'image
                $file = $promo->getImage();
                $fileName = $file->getFilename();
                $img = $fileName . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $img);
                $promo->setImage($img);
            } else {
                $promo->setImage($promoImage);
            }

            $this->entityManager->persist($promo);
            $this->entityManager->flush();
        }

        return $this->render('admin/autopromo/create.html.twig', [
                    'formPromo' => $form->createView(),
                    'image' => $promo->getImage()
        ]);
    }

    /**
     * @Route("/admin/autopromo/delete/{id}", name="delete_promo")
     */
    public function deletePromo($id, AutoPromoRepository $promo) {
        $promo = $promo->find($id);
        $this->entityManager->remove($promo);
        $this->entityManager->flush();

        return $this->redirectToRoute('bo_auto_promo');
    }

}
