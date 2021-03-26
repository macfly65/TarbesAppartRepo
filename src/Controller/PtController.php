<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\appartement;
use App\Repository\appartementRepository;
use App\Entity\PT;
use App\Repository\PTRepository;
use Symfony\Component\HttpFoundation\Request;




class PtController extends AbstractController
{
    /**
     * @Route("/admin/problème_technique", name="admin_problème_technique")
     */
    public function index(PTRepository $ptRepo, Request $request)
    {
        $ptList = $ptRepo->findAll();

        // Gestion des filtres
       // $search = new PropertySearch();
      /*  $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($_GET['numAppart'] != "" || $_GET['residence'] != "0") {
                $searchResidence = $searchAppart = 0;
                $searchAppart = $_GET['numAppart'];
                $searchResidence = $_GET['residence'];
                $appartList = $appart->findAppartAdmin($searchAppart, $searchResidence);
            }
        }                             */

        return $this->render('admin/admin_PT/index.html.twig', [
            'PTlist' => $ptList,
        ]);
    }
}