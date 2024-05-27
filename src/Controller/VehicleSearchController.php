<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AvailabilityRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchAvailabilityType;
use Psr\Log\LoggerInterface;

class VehicleSearchController extends AbstractController
{
    #[Route('/search', name: 'vehicle_search')]
    public function search(Request $request, AvailabilityRepository $availabilityRepository, LoggerInterface $logger): Response
    {
        $form = $this->createForm(SearchAvailabilityType::class);
        $form->handleRequest($request);

        $vehicles = [];
        $totalPrices = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $departDate = $data['depart_date'];
            $returnDate = $data['return_date'];

            // Form data for debugging
            $logger->info('Form submitted with data: ', $data);

            // Dates for debugging
            $logger->info('Depart Date: ' . $departDate->format('Y-m-d'));
            $logger->info('Return Date: ' . $returnDate->format('Y-m-d'));


            // Available vehicles for the given date range
            $availabilities = $availabilityRepository->findAvailableVehicles($departDate, $returnDate);

            if ($availabilities) {
                $logger->info('Availabilities found: ', ['count' => count($availabilities)]);
            } else {
                $logger->info('No availabilities found for the given date range.');
            }

            foreach ($availabilities as $availability) {
                $vehicle = $availability->getVehicle();
                $days = $returnDate->diff($departDate)->days + 1;
                $totalPrice = $availability->getPricePerDay() * $days;

                if (!in_array($vehicle, $vehicles, true)) {
                    $vehicles[] = $vehicle;
                }
                $totalPrices[$vehicle->getId()] = $totalPrice;
            }
        } else {
            // Log form errors for debugging
            if ($form->isSubmitted()) {
                $logger->error('Form submission errors: ' . (string) $form->getErrors(true, false));
            }
        }

        return $this->render('vehicle_search/index.html.twig', [
            'form' => $form->createView(),
            'vehicles' => $vehicles,
            'totalPrices' => $totalPrices,
        ]);
    }
}
