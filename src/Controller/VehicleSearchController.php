<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AvailabilityRepository;
use Symfony\Component\HttpFoundation\Request; // Import the missing class
use App\Form\SearchAvailabilityType; // Import the missing class

class VehicleSearchController extends AbstractController
{
    #[Route('/search', name: 'vehicle_search')]
    public function search(Request $request, AvailabilityRepository $availabilityRepository): Response
    {
        $form = $this->createForm(SearchAvailabilityType::class);
        $form->handleRequest($request);

        $vehicles = [];
        $totalPrices = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $departDate = $data['depart_date'];
            $returnDate = $data['return_date'];

            // Find available vehicles for the given date range
            $availabilities = $availabilityRepository->findAvailableVehicles($departDate, $returnDate);

            foreach ($availabilities as $availability) {
                $vehicle = $availability->getVehicle();
                $days = $returnDate->diff($departDate)->days;
                $totalPrice = $availability->getPricePerDay() * $days;

                $vehicles[] = $vehicle;
                $totalPrices[$vehicle->getId()] = $totalPrice;
            }
        }

        return $this->render('vehicle_search/index.html.twig', [
            'form' => $form->createView(),
            'vehicles' => $vehicles,
            'totalPrices' => $totalPrices,
        ]);
    }
}
