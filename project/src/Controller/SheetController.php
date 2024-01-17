<?php

namespace App\Controller;

use App\Entity\Sheet;
use App\Repository\InstrumentRepository;
use App\Repository\SheetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SheetController extends AbstractController
{
    #[Route('/api/sheets', name: 'api_sheets', methods: ['GET'])]
    public function getSheetList(SheetRepository $sheetRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $offset = $request->get('offset', 1);
        $limit = $request->get('limit', 5);

        $sheetList = $sheetRepository->findAllWithPagination($offset, $limit);
        
        $jsonsheetList = $serializer->serialize($sheetList, 'json', ['groups' => 'sheet:read']);
        return new JsonResponse ($jsonsheetList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/sheets/{id}', name: 'api_sheets_detail', methods: ['GET'])]
    public function getSheetDetail(Sheet $sheet, SerializerInterface $serializer): JsonResponse
    {
        $jsonSheet = $serializer->serialize($sheet, 'json', ['groups' => 'sheet:read']);
        return new JsonResponse($jsonSheet, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    
    #[Route('/api/sheets/{id}', name: 'api_sheets_delete', methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function deleteSheet(Sheet $sheet, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($sheet);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/sheets', name: 'api_sheets_create', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function createSheet(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, InstrumentRepository $instrumentRepository, ValidatorInterface $validator): JsonResponse
    {
        $sheet = $serializer->deserialize($request->getContent(), Sheet::class, 'json');
        
        $content = $request->toArray();

        $idInstrument = $content['idInstrument'] ?? -1;

        $sheet->setInstrument($instrumentRepository->find($idInstrument));

        $errors = $validator->validate($sheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($sheet);
        $em->flush();

        $jsonSheet = $serializer->serialize($sheet, 'json', ['groups' => 'sheet:read']);
        $location = $urlGenerator->generate('api_sheets_detail', ['id' => $sheet->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonSheet, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/sheets/{id}', name: 'api_sheets_update', methods: ['PUT'])]
    public function updateSheets(Request $request, Sheet $currentSheet, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, InstrumentRepository $instrumentRepository): JsonResponse 
    {
        $updatedSheet = $serializer->deserialize($request->getContent(), 
                Sheet::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentSheet]);

        $content = $request->toArray();
        $idInstrument = $content['idInstrument'] ?? -1;
        if ($idInstrument != -1){
            $updatedSheet->setInstrument($instrumentRepository->find($idInstrument));
        }
        
        // Validation des données
        $errors = $validator->validate($updatedSheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedSheet);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
