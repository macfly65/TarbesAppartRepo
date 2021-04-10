<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ExtranetController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/extranet", name="extranet")
     */
    public function index()
    {
        $user = $this->security->getUser();

        return $this->render('extranet/index.html.twig', [
            'user' => $user,
        ]);
    }
}
