<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Repository\AppartementRepository;
use App\Entity\AppartementMedias;
use App\Repository\AppartementMediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class FrontAppartController extends AbstractController
{
    /**
     * @Route("/appartement", name="front_appart")
     */
    public function index(AppartementRepository $appartement)
    {
        $appartements = $appartement->findAppartfront();
        $appartementsBy3 = $appartement->findAppartfrontby3();

        
        return $this->render('front_appart/index.html.twig', [
            'appartements' => $appartements,
            'appartementsBy3' => $appartementsBy3
        ]);
    }
    
        /**
     * @Route("/appartement/fiche/{id}", name="fiche_appart")
     */
    public function appartementFiche($id, AppartementRepository $appartement, AppartementMediasRepository $medias)
    {
        $appartements = $appartement->find($id);
        $media = $medias->findBy(['appartement' => $id]);
        $allAppart = $appartement->findAll(); 

        return $this->render('front_appart/fiche.html.twig', [
            'appartement' => $appartements,
            'media' => $media,
            'allAppart' => $allAppart
        ]);
    }
    
    
}
