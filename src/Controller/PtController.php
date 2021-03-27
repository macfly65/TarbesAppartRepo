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
use Symfony\Component\HttpClient\CurlHttpClient;
use Mailjet\Resources;
use App\Service\Notification;





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
               $prestaNom = $prestaRepo->find($PT->getPrestataire()->getNom());
        }

        return $this->render('admin/admin_PT/index.html.twig', [
            'PTlist' => $ptList,
            'appart' => $appart,
            'locataire' => $locataire,
            'prestaNom' => $prestaNom
        ]);
    }

    /**
     * @Route("/admin/edit_problème_technique/{id}", name="admin_edit_problème_technique")
     */
    public function add(PrestataireRepository $prestaRepo, appartementRepository $appartRepo, PTRepository $ptRepo, Request $request, $id = null, Notification $Notification)
    {
        if ($id != null) {
            $pt = $ptRepo->find($id);
            $ptNew = 0;
        }else{
            $pt = new PT();
            $ptNew = 1;
        }

        $form = $this->createForm(PTFormType::class,$pt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->entityManager->persist($pt);
            $this->entityManager->flush();

            if($ptNew == 1){
              // $this->sendNotification($pt);
                $Notification->sendNotifMail($pt);
               // $Notification->sendNotifSms($pt->getAppartement()->getResidence()->getNom(), $pt->getPrestataire()->getTel());
            }
        }
        return $this->render('admin/admin_PT/edit.html.twig', [
            'pt' => $pt,
            'form'=> $form->createView()
        ]);
    }
}