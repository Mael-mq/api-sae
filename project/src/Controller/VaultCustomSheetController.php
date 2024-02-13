<?php

namespace App\Controller;

use App\Entity\VaultCustomSheet;
use App\Repository\CustomSheetRepository;
use App\Repository\UserRepository;
use App\Repository\VaultCustomSheetRepository;
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

class VaultCustomSheetController extends AbstractController
{
    #[Route('/api/vault-custom-sheets', name: 'api_vault_custom_sheets', methods: ['GET'])]
    public function getVaultCustomSheetList(VaultCustomSheetRepository $vaultCustomSheetRepository, UserRepository $userRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $vaultCustomSheetList = $vaultCustomSheetRepository->findBy(['User' => $user]);
        
        $jsonVaultSheetList = $serializer->serialize($vaultCustomSheetList, 'json', ['groups' => 'customSheet:read']);
        return new JsonResponse ($jsonVaultSheetList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/vault-custom-sheets/{id}', name: 'api_vault_custom_sheets_detail', methods: ['GET'])]
    public function getVaultCustomSheetDetail(UserRepository $userRepository, VaultCustomSheet $vaultCustomSheet, SerializerInterface $serializer): JsonResponse
    {
        $userRequest = $vaultCustomSheet->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $jsonVaultCustomSheet = $serializer->serialize($vaultCustomSheet, 'json', ['groups' => 'customSheet:read']);
        return new JsonResponse($jsonVaultCustomSheet, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/vault-custom-sheets/{id}', name: 'api_vault_custom_sheets_delete', methods: ['DELETE'])]
    public function deleteVaultCustomSheet(UserRepository $userRepository, VaultCustomSheet $vaultCustomSheet, EntityManagerInterface $em): JsonResponse
    {
        $userRequest = $vaultCustomSheet->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $em->remove($vaultCustomSheet);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/vault-custom-sheets', name: 'api_vault_custom_sheet_create', methods: ['POST'])]
    public function createVaultCustomSheet(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, CustomSheetRepository $customSheetRepository, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $vaultCustomSheet = $serializer->deserialize($request->getContent(), VaultCustomSheet::class, 'json');
        
        $content = $request->toArray();
        $idCustomSheet = $content['idCustomSheet'] ?? -1;
        $vaultCustomSheet->setCustomSheet($customSheetRepository->find($idCustomSheet));

        $user = $userRepository->getUserFromToken();
        $vaultCustomSheet->setUser($user);

        // Validation des données
        $errors = $validator->validate($vaultCustomSheet);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($vaultCustomSheet);
        $em->flush();

        $jsonVaultCustomSheet = $serializer->serialize($vaultCustomSheet, 'json', ['groups' => 'customSheet:read']);
        $location = $urlGenerator->generate('api_vault_custom_sheets_detail', ['id' => $vaultCustomSheet->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonVaultCustomSheet, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/vault-custom-sheets/{id}', name: 'api_vault_custom_sheet_update', methods: ['PUT'])]
    public function updateVaultCustomSheet(UserRepository $userRepository, Request $request, SerializerInterface $serializer, VaultCustomSheet $vaultCustomSheet, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse 
    {
        $userRequest = $vaultCustomSheet->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $content = $request->toArray();

        if($content['isFavorite'] === true || $content['isFavorite'] === false){
            $vaultCustomSheet->setIsFavorite($content['isFavorite']);
        }

        $em->persist($vaultCustomSheet);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
