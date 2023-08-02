<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\AdvertisementFormType;
use App\Repository\AdvertisementRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(Request $request, AdvertisementRepository $repo): Response
    {
        $sortOption = $request->query->get('sort', 'recent');
        $orderBy = ($sortOption === 'cheapest') ? ['price' => 'ASC'] : ['id' => 'DESC'];
        $advertisements = $repo->findBy([], $orderBy);

        return $this->render('pages/index.html.twig', [
            'ads' => $advertisements,
            'selectedSortOption' => $sortOption,
        ]);
    }

    #[Route('/ad/new', name: 'ad_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $advertisement = new Advertisement();
        $adForm = $this->createForm(AdvertisementFormType::class, $advertisement);
        $adForm->handleRequest($request);

        if ($adForm->isSubmitted() && $adForm->isValid()) {
            $this->handleImageUpload($adForm, $slugger, $advertisement);
            $advertisement->setCreatedAt(new DateTimeImmutable('now'));
            $em->persist($advertisement);
            $em->flush();
            $this->addFlash('success', 'Votre annonce a été publiée');

            return $this->redirectToRoute('ad_show', ['id' => $advertisement->getId()]);
        }

        return $this->render('pages/create.html.twig', [
            'adForm' => $adForm->createView()
        ]);
    }

    #[Route('/ad/{id}', name: 'ad_show')]
    public function show(Advertisement $advertisement): Response
    {
        return $this->render('pages/ad.html.twig', [
            'ad' => $advertisement
        ]);
    }

    #[Route('/ad/{id}/delete', name: 'ad_delete')]
    public function delete(Advertisement $advertisement, EntityManagerInterface $em): Response
    {
        $em->remove($advertisement);
        $em->flush();
        $this->addFlash('success', 'Annonce supprimée');

        return $this->redirectToRoute('home');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('pages/dashboard.html.twig');
    }

    #[Route('/ad/{id}/edit', name: 'ad_edit')]
    public function edit(Request $request, Advertisement $advertisement, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $adForm = $this->createForm(AdvertisementFormType::class, $advertisement);
        $adForm->handleRequest($request);

        if ($adForm->isSubmitted() && $adForm->isValid()) {
            $this->handleImageUpload($adForm, $slugger, $advertisement);
            $em->flush();
            $this->addFlash('success', 'Annonce mise à jour');

            return $this->redirectToRoute('ad_show', ['id' => $advertisement->getId()]);
        }

        return $this->render('pages/edit.html.twig', [
            'adForm' => $adForm->createView(),
            'ad' => $advertisement,
        ]);
    }

    private function handleImageUpload($adForm, $slugger, $advertisement): void
    {
        /** @var UploadedFile $imageFile */
        $imageFile = $adForm->get('imageFile')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move($this->getParameter('image_directory'), $newFilename);
            } catch (FileException $e) {
                // Handle the exception if something goes wrong during file upload
                // For example, display an error message or log the error
            }

            $advertisement->setImageFilename($newFilename);
        }
    }
}

