<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Repository\InstrumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class InstrumentController extends AbstractController
{
    #[Route('/api/instruments', name: 'api_instrument', methods: ['GET'])]
    public function getInstrumentList(InstrumentRepository $instrumentRepository, SerializerInterface $serializer): JsonResponse
    {
        $instrumentList = $instrumentRepository->findAll();
        $jsonInstrumentList = $serializer->serialize($instrumentList, 'json');
        return new JsonResponse ($jsonInstrumentList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/instruments/{id}', name: 'api_instrument_detail', methods: ['GET'])]
    public function getInstrumentDetail(Instrument $instrument, SerializerInterface $serializer): JsonResponse
    {
        $jsonInstrument = $serializer->serialize($instrument, 'json');
        return new JsonResponse($jsonInstrument, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
