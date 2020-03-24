<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AutoPromoController extends AbstractController
{
    /**
     * @Route("/auto/promo", name="auto_promo")
     */
    public function index()
    {
        return $this->render('auto_promo/index.html.twig', [
            'controller_name' => 'AutoPromoController',
        ]);
    }
}
