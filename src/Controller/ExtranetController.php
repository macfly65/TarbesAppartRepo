<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExtranetController extends AbstractController
{
    /**
     * @Route("/extranet", name="extranet")
     */
    public function index()
    {
        return $this->render('extranet/index.html.twig', [
            'controller_name' => 'ExtranetController',
        ]);
    }
}
