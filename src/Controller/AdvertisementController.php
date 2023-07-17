<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $nom = "danyl";

        return $this->render('home/index.html.twig', [
            "nom" => $nom
        ]);

    }
}