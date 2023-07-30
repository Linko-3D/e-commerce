<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use App\Form\AdvertisementFormType;
use DateTime;
use DateTimeImmutable;
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

            $ad->setCreatedAt(new DateTimeImmutable('now'));

            $em->persist($ad);
            $em->flush();

            $this->addFlash('success', 'Votre annonce a été publiée');

            // Redirect to the route of the newly created advertisement with its ID
            return $this->redirectToRoute('ad_show', ['id' => $ad->getId()]);
        } else {
            $this->addFlash('error', 'Erreur, votre annonce n\'a pas pu être publiée');
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

    #[Route('/ad/{id}/delete', name: 'ad_delete')]
    public function delete(Advertisement $advertisement, EntityManagerInterface $em): Response
    {
        // Delete the advertisement
        $em->remove($advertisement);
        $em->flush();

        // Set a flash message for success
        $this->addFlash('success', 'Annonce supprimée');

        // Redirect to the home page after successful deletion
        return $this->redirectToRoute('home');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('pages/dashboard.html.twig');
    }

    #[Route('/ad/{id}/edit', name: 'ad_edit')]
    public function edit(Request $request, Advertisement $advertisement, EntityManagerInterface $em): Response
    {
        $adForm = $this->createForm(AdvertisementFormType::class, $advertisement);

        $adForm->handleRequest($request);

        if ($adForm->isSubmitted() && $adForm->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Annonce mise à jour');

            return $this->redirectToRoute('ad_show', ['id' => $advertisement->getId()]);
        }

        return $this->render('pages/edit.html.twig', [
            'adForm' => $adForm->createView(),
            'ad' => $advertisement,
        ]);
    }
}
