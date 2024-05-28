<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Form\AvailabilityType;
use App\Repository\AvailabilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VehicleRepository;
use App\Entity\Vehicle;

#[Route('/availability')]
class AvailabilityController extends AbstractController
{
    #[Route('/', name: 'app_availability_index', methods: ['GET'])]
    public function index(AvailabilityRepository $availabilityRepository): Response
    {
        $availabilities = $availabilityRepository->findAll();

        return $this->render('availability/index.html.twig', [
            'availabilities' => $availabilityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_availability_new', methods: ['GET', 'POST'])]
    #[Route('/new/{vehicleId}', name: 'app_availability_new_with_vehicle', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository, $vehicleId = null): Response
    {
        $availability = new Availability();
        $form = $this->createForm(AvailabilityType::class, $availability);

        if ($vehicleId) {
            $vehicle = $vehicleRepository->find($vehicleId);
            if ($vehicle) {
                $availability->setVehicle($vehicle);
                $form = $this->createForm(AvailabilityType::class, $availability, [
                    'vehicle' => $vehicle
                ]);
            }
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $availability->setStatus($form->get('status')->getData());

            $entityManager->persist($availability);
            $entityManager->flush();

            return $this->redirectToRoute('app_availability_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('availability/new.html.twig', [
            'availability' => $availability,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_availability_show', methods: ['GET'])]
    public function show(Availability $availability): Response
    {
        return $this->render('availability/show.html.twig', [
            'availability' => $availability,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_availability_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Availability $availability, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $availability->setStatus($form->get('status')->getData());

            $entityManager->flush();

            return $this->redirectToRoute('app_availability_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('availability/edit.html.twig', [
            'availability' => $availability,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_availability_delete', methods: ['POST'])]
    public function delete(Request $request, Availability $availability, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$availability->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($availability);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_availability_index', [], Response::HTTP_SEE_OTHER);
    }
}
