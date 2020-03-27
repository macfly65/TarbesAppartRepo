<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\AutoPromo;
use App\Repository\AutoPromoRepository;


class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(AutoPromoRepository $autoPromoRepository)
    {
        $autoPromo = $autoPromoRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'autoPromoList' => $autoPromo
        ]);
    }
}
