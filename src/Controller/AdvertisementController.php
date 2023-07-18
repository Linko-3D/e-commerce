<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(AdvertisementRepository $repo): Response
    {
        $advertisements = $repo->findBy([], ['id' => 'DESC']);

        return $this->render('pages/index.html.twig', [
            'ads' => $advertisements
        ]);
    }

    #[Route('/ad/new', name: 'ad_create')]
    public function create()
    {
        return $this->render('pages/create.html.twig');
    }

    #[Route('/ad/{id}', name: 'ad_show')]
    public function show($id, AdvertisementRepository $repo): Response
    {
        $advertisements = $repo->find($id);

        return $this->render('pages/ad.html.twig', [
            'ad' => $advertisements
        ]);
    }
}
