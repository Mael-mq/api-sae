<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInstrument;
use App\Repository\InstrumentRepository;
use App\Repository\UserInstrumentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserInstrumentController extends AbstractController
{
    #[Route('/api/user-instruments', name: 'api_user_instruments', methods: ['GET'])]
    public function getUserInstrumentList(UserRepository $userRepository, UserInstrumentRepository $userInstrumentRepository, SerializerInterface $serializer): JsonResponse
    {
        $userInstrumentList = $userInstrumentRepository->findBy(['User'=>$userRepository->getUserFromToken()]);
        
        $jsonInstrumentList = $serializer->serialize($userInstrumentList, 'json', ['groups' => 'userInstrument:read']);
        return new JsonResponse ($jsonInstrumentList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/user-instruments/{id}', name: 'api_user_instruments_detail', methods: ['GET'])]
    public function getUserInstrumentDetail(UserRepository $userRepository, UserInstrument $userInstrument, SerializerInterface $serializer): JsonResponse
    {
        // Vérifier que l'utilisateur connecté est bien celui qui a ajouté l'instrument
        $userRequest = $userInstrument->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $jsonUserInstrument = $serializer->serialize($userInstrument, 'json', ['groups' => 'userInstrument:read']);
        return new JsonResponse($jsonUserInstrument, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/user-instruments/{id}', name: 'api_user_instruments_delete', methods: ['DELETE'])]
    public function deleteUserInstrument(UserInstrument $userInstrument, UserRepository $userRepository, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {   
        // Vérifier que l'utilisateur connecté est bien celui qui a ajouté l'instrument
        $userRequest = $userInstrument->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $em->remove($userInstrument);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/user-instruments', name: 'api_user_instruments_create', methods: ['POST'])]
    public function createUserInstrument(Request $request, UserRepository $userRepository, InstrumentRepository $instrumentRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $user = $userRepository->getUserFromToken();
        
        $content = $request->toArray();
        $instrumentList = $content['instrumentList'] ?? -1;
        if($instrumentList == -1){
            return new JsonResponse("Vous devez spécifier une liste d'instruments.", Response::HTTP_BAD_REQUEST);
        }
        foreach($instrumentList as $instrumentId){
            $userInstrument = new UserInstrument();
            $userInstrument->setUser($user);

            $instrument = $instrumentRepository->find($instrumentId);
            $userInstrument->setInstrument($instrument);
            $errors = $validator->validate($userInstrument);
            if(count($errors) > 0) {
                return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
            $em->persist($userInstrument);
            $em->flush();
        }

        $jsonUserInstrument = $serializer->serialize($userInstrument, 'json', ['groups' => 'userInstrument:read']);
        $location = $urlGenerator->generate('api_user_instruments', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUserInstrument, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
