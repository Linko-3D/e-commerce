<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\AdvertisementType;
use App\Repository\AdvertisementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'app_advertisement_index', methods: ['GET'])]
    public function index(AdvertisementRepository $advertisementRepository): Response
    {
        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $advertisementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_advertisement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($advertisement);
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('advertisement/new.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advertisement_show', methods: ['GET'])]
    public function show(Advertisement $advertisement): Response
    {
        return $this->render('advertisement/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_advertisement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('advertisement/edit.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advertisement_delete', methods: ['POST'])]
    public function delete(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advertisement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($advertisement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_advertisement_index', [], Response::HTTP_SEE_OTHER);
    }
}
