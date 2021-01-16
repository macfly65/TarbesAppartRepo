<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\AutoPromo;
use App\Repository\AutoPromoRepository;

use App\Entity\Actualite;
use App\Repository\ActualiteRepository;

use App\Entity\Appartement;
use App\Repository\AppartementRepository;


class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(AutoPromoRepository $autoPromoRepository, ActualiteRepository $actualite, AppartementRepository $appartement)
    {
        $autoPromo = $autoPromoRepository->findBy(array(), array('ordre' => 'ASC'));
        $actualites = $actualite->findActualitefront();
        $appartements = $appartement->findAll();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'autoPromoList' => $autoPromo,
            'actualites' => $actualites,
            'appartements' => $appartements,
        ]);
    }
}
