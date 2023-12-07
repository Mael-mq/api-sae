<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class InstrumentController extends AbstractController
{
    #[Route('/api/instruments', name: 'api_instrument', methods: ['GET'])]
    public function getInstruments(): JsonResponse
    {
        return new JsonResponse ([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/InstrumentController.php',
        ]);
    }
}
