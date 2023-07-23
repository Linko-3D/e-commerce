<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use App\Form\AdvertisementFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $ad = new Advertisement();

        $adForm = $this->createForm(AdvertisementFormType::class, $ad);

        $adForm->handleRequest($request);

        if ($adForm->isSubmitted() && $adForm->isValid()) {
            // $slug = $slugger->slug($ad->getTitle());
            // $ad->setSlug($slug);

            $em->persist($ad);
            $em->flush();

            $this->addFlash('success', 'Annonce publiée');

            // Redirect to the route of the newly created advertisement with its ID
            return $this->redirectToRoute('ad_show', ['id' => $ad->getId()]);
        }

        return $this->render('pages/create.html.twig', [
            'adForm' => $adForm->createView()
        ]);
    }

    #[Route('/ad/{id}', name: 'ad_show')]
    public function show($id, AdvertisementRepository $repo): Response
    {
        $advertisement = $repo->find($id);

        return $this->render('pages/ad.html.twig', [
            'ad' => $advertisement
        ]);
    }


   #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {

        return $this->render('pages/dashboard.html.twig');
    }


}
