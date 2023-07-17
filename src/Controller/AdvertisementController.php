<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advertisement;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        // $repo = $this->getDoctrine()->getRepository(Advertisement::class);
        // $article = $repo->findAll();

        $nom = "danyl";

        return $this->render('home/index.html.twig', [
            "nom" => $nom
        ]);

    }
}