<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\CatalogueCategs;
use App\Entity\CatalogueMedias;
use App\Entity\Catalogue;
use App\Repository\CatalogueCategsRepository;
use App\Repository\CatalogueRepository;
use App\Repository\CatalogueMediasRepository;

class FrontCatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue/{vin}", name="front_catalogue")
     */
    public function index($vin = null, CatalogueRepository $catalogues ,CatalogueCategsRepository $catalogueCateges, CatalogueMediasRepository $medias)
    {
        $categories = $catalogueCateges->findBy(array(), array('ordre' => 'ASC'));
        
        
        if(isset($vin) != null){
           $catalogues = $catalogues->findBy(['categorieId' => $vin]);
        }else{
           $catalogues = $catalogues->findAll();
        }

        return $this->render('front_catalogue/index.html.twig', [
            'controller_name' => 'FrontCatalogueController',
            'categories' => $categories,
            'catalogues' => $catalogues,
//            'test' => $test
        ]);
    }
    
    /**
     * @Route("/catalogue/fiche/{id}", name="fiche_catalogue")
     */
    public function showOnePromo($id, CatalogueCategsRepository $catalogue) {
        $catalogue = new Catalogue;
       

        return $this->render('admin_catalogue/fiche.html.twig', [
                   
        ]);
    }

}
