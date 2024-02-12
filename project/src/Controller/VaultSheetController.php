<?php

namespace App\Controller;

use App\Entity\VaultSheet;
use App\Repository\SheetRepository;
use App\Repository\UserRepository;
use App\Repository\VaultSheetRepository;
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

class VaultSheetController extends AbstractController
{
    #[Route('/api/vault-sheets', name: 'api_vault_sheets', methods: ['GET'])]
    public function getVaultList(VaultSheetRepository $vaultSheetRepository, UserRepository $userRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $vaultSheetList = $vaultSheetRepository->findBy(['User' => $user]);
        
        $jsonVaultSheetList = $serializer->serialize($vaultSheetList, 'json', ['groups' => 'vaultSheet:read']);
        return new JsonResponse ($jsonVaultSheetList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/vault-sheets/{id}', name: 'api_vault_sheets_detail', methods: ['GET'])]
    public function getVaultSheetDetail(UserRepository $userRepository, VaultSheet $vaultSheet, SerializerInterface $serializer): JsonResponse
    {
        $userRequest = $vaultSheet->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $jsonVaultSheet = $serializer->serialize($vaultSheet, 'json', ['groups' => 'vaultSheet:read']);
        return new JsonResponse($jsonVaultSheet, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/vault-sheets/{id}', name: 'api_vault_sheets_delete', methods: ['DELETE'])]
    public function deleteVaultSheet(UserRepository $userRepository, VaultSheet $vaultSheet, EntityManagerInterface $em): JsonResponse
    {
        $userRequest = $vaultSheet->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $em->remove($vaultSheet);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/vault-sheets', name: 'api_vault_sheet_create', methods: ['POST'])]
    public function createVaultSheet(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, SheetRepository $sheetRepository, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $vaultSheet = $serializer->deserialize($request->getContent(), VaultSheet::class, 'json');
        
        $content = $request->toArray();
        $idSheet = $content['idSheet'] ?? -1;
        $vaultSheet->setSheet($sheetRepository->find($idSheet));

        $user = $userRepository->getUserFromToken();
        $vaultSheet->setUser($user);

        if(!isset($content['isFavorite'])){
            $vaultSheet->setIsFavorite(false);
        }

        // Validation des données
        $errors = $validator->validate($vaultSheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($vaultSheet);
        $em->flush();

        $jsonVaultSheet = $serializer->serialize($vaultSheet, 'json', ['groups' => 'vaultSheet:read']);
        $location = $urlGenerator->generate('api_vault_sheet_detail', ['id' => $vaultSheet->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonVaultSheet, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/vault-sheets/{id}', name: 'api_vault_sheet_update', methods: ['PUT'])]
    public function updateVaultSheet(UserRepository $userRepository, Request $request, SerializerInterface $serializer, VaultSheet $currentVaultSheet, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse 
    {
        $updatedVaultSheet = $serializer->deserialize($request->getContent(), 
                VaultSheet::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentVaultSheet]);
        
        $userRequest = $updatedVaultSheet->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $errors = $validator->validate($updatedVaultSheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedVaultSheet);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
