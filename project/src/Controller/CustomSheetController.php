<?php

namespace App\Controller;

use App\Entity\CustomSheet;
use App\Repository\CustomSheetRepository;
use App\Repository\InstrumentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomSheetController extends AbstractController
{
    // #[Route('/api/custom-sheets', name: 'api_custom_sheets', methods: ['GET'])]
    // public function getCustomSheetList(CustomSheetRepository $customSheetRepository, SerializerInterface $serializer): JsonResponse
    // {
    //     $customSheetList = $customSheetRepository->findAll();
        
    //     $jsonCustomSheetList = $serializer->serialize($customSheetList, 'json', ['groups' => 'customSheet:read']);
    //     return new JsonResponse ($jsonCustomSheetList, Response::HTTP_OK, ['accept' => 'json'], true);
    // }

    #[Route('/api/custom-sheets/{id}', name: 'api_custom_sheets_detail', methods: ['GET'])]
    public function getCustomSheetDetail(CustomSheet $customSheet, SerializerInterface $serializer): JsonResponse
    {
        $jsonSheet = $serializer->serialize($customSheet, 'json', ['groups' => 'customSheet:read']);
        return new JsonResponse($jsonSheet, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    
    #[Route('/api/custom-sheets/{id}', name: 'api_custom_sheets_delete', methods: ['DELETE'])]
    public function deleteCustomSheet(CustomSheet $customSheet, EntityManagerInterface $em, UserRepository $userRepository): JsonResponse
    {
        if($customSheet->getAuthor() != $userRepository->getUserFromToken()){
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }
        $vaultCustomSheets = $customSheet->getVaultCustomSheets();
        foreach ($vaultCustomSheets as $vaultCustomSheet){
            $em->remove($vaultCustomSheet);
        }
        $em->remove($customSheet);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/custom-sheets', name: 'api_custom_sheets_create', methods: ['POST'])]
    public function createCustomSheet(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, InstrumentRepository $instrumentRepository, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $customSheet = $serializer->deserialize($request->getContent(), CustomSheet::class, 'json');
        
        $content = $request->toArray();

        $idInstrument = $content['idInstrument'] ?? -1;
        
        $customSheet->setInstrument($instrumentRepository->find($idInstrument));
        $customSheet->setAuthor($userRepository->getUserFromToken());

        $errors = $validator->validate($customSheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($customSheet);
        $em->flush();

        $jsonSheet = $serializer->serialize($customSheet, 'json', ['groups' => 'customSheet:read']);
        $location = $urlGenerator->generate('api_custom_sheets_detail', ['id' => $customSheet->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonSheet, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/custom-sheets/{id}', name: 'api_custom_sheets_update', methods: ['PUT'])]
    public function updateCustomSheets(Request $request, CustomSheet $currentCustomSheet, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserRepository $userRepository, InstrumentRepository $instrumentRepository): JsonResponse 
    {
        if($currentCustomSheet->getAuthor() != $userRepository->getUserFromToken()){
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $updatedCustomSheet = $serializer->deserialize($request->getContent(), 
                CustomSheet::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCustomSheet]);

        $content = $request->toArray();
        $idInstrument = $content['idInstrument'] ?? -1;
        if ($idInstrument != -1){
            $updatedCustomSheet->setInstrument($instrumentRepository->find($idInstrument));
        }
        
        // Validation des données
        $errors = $validator->validate($updatedCustomSheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedCustomSheet);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
