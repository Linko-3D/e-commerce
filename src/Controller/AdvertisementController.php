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

        return $this->render('home/index.html.twig', [
            'advertisements' => $advertisements
        ]);
    }

    #[Route('/ad/{id}', name: 'ad')]
    public function show($id, AdvertisementRepository $repo): Response
    {
        $advertisements = $repo->find($id);
        return $this->render('home/ad.html.twig', [
            'advertisements' => $advertisements
        ]);
    }
}