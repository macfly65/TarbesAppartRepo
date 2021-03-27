<?php

namespace App\Controller;

use App\Form\PTFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AppartementRepository;
use App\Repository\PrestataireRepository;
use App\Entity\PT;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PTRepository;
use Symfony\Component\HttpFoundation\Request;




class PtController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/problème_technique", name="admin_problème_technique")
     */
    public function index(PrestataireRepository $prestaRepo, appartementRepository $appartRepo, PTRepository $ptRepo, Request $request)
    {
        $ptList = $ptRepo->findAll();

        foreach ($ptList as $PT){
           $appart = $appartRepo->find($PT->getAppartement());
           $locataire = $appart->getLocataire()->toArray();
           $presta = $prestaRepo->find($PT->getPrestataire());
        }

        return $this->render('admin/admin_PT/index.html.twig', [
            'PTlist' => $ptList,
            'appart' => $appart,
            'locataire' => $locataire
        ]);
    }

    /**
     * @Route("/admin/edit_problème_technique/{id}", name="admin_edit_problème_technique")
     */
    public function add(PrestataireRepository $prestaRepo, appartementRepository $appartRepo, PTRepository $ptRepo, Request $request, $id = null)
    {
        if ($id != null) {
            $pt = $ptRepo->find($id);
        }else{
            $pt = new PT();
        }


        $form = $this->createForm(PTFormType::class,$pt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            $this->entityManager->persist($pt);
            $this->entityManager->flush();
        }







        return $this->render('admin/admin_PT/edit.html.twig', [
            'pt' => $pt,
            'form'=> $form->createView()
        ]);
    }
}