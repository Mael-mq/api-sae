<?php

namespace App\Controller;

use App\Entity\ExerciceAppUser;
use App\Repository\ExerciceAppRepository;
use App\Repository\ExerciceAppUserRepository;
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

class ExerciceAppUserController extends AbstractController
{
    #[Route('/api/exercice-app-user', name: 'api_exercice_app_user', methods: ['GET'])]
    public function getExerciceAppList(UserRepository $userRepository, ExerciceAppUserRepository $exerciceAppUserRepository, SerializerInterface $serializer): JsonResponse
    {
        $exerciceAppUserList = $exerciceAppUserRepository->findBy(['User'=>$userRepository->getUserFromToken()]);
        
        $jsonExerciceAppUserList = $serializer->serialize($exerciceAppUserList, 'json', ['groups' => 'exerciceAppUser:read']);
        return new JsonResponse ($jsonExerciceAppUserList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/exercice-app-user/{id}', name: 'api_exercice_app_user_detail', methods: ['GET'])]
    public function getExerciceAppDetail(UserRepository $userRepository, ExerciceAppUser $exerciceAppUser, SerializerInterface $serializer): JsonResponse
    {
        // Vérifier que l'utilisateur connecté est bien celui qui veut récupérer l'exercice
        $userRequest = $exerciceAppUser->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $jsonExerciceAppUser = $serializer->serialize($exerciceAppUser, 'json', ['groups' => 'exerciceAppUser:read']);
        return new JsonResponse($jsonExerciceAppUser, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/exercice-app-user/{id}', name: 'api_exercice_app_user_modify', methods: ['PUT'])]
    public function modifyExerciceAppUser(Request $request, UserRepository $userRepository, ExerciceAppUser $currentExerciceAppUser, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        // Vérifier que l'utilisateur connecté est bien celui qui veut modifier le statut de l'exercice
        $userRequest = $currentExerciceAppUser->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $updatedExerciceAppUser = $serializer->deserialize($request->getContent(), ExerciceAppUser::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentExerciceAppUser]);
        
        $currentExerciceAppUser->setExerciceApp($currentExerciceAppUser->getExerciceApp());
        $currentExerciceAppUser->setUser($currentExerciceAppUser->getUser());

        // Validation des données
        $errors = $validator->validate($updatedExerciceAppUser);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedExerciceAppUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/exercice-app-user', name: 'api_exercice_app_create', methods: ['POST'])]
    public function createExerciceAppUser(Request $request, UserRepository $userRepository, ExerciceAppRepository $exerciceAppRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $exerciceAppUser = new ExerciceAppUser();
        $exerciceAppUser->setUser($user);

        $content = $request->toArray();
        $idExerciceApp = $content['idExerciceApp'] ?? -1;
        $exerciceApp = $exerciceAppRepository->find($idExerciceApp);
        $exerciceAppUser->setExerciceApp($exerciceApp);

        $exerciceAppUser->setIsFinished(false);

        $errors = $validator->validate($exerciceAppUser);
        if(count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($exerciceAppUser);
        $em->flush();
        

        $jsonExerciceAppUser = $serializer->serialize($exerciceAppUser, 'json', ['groups' => 'exerciceAppUser:read']);
        $location = $urlGenerator->generate('api_exercice_app_user_detail', ['id' => $exerciceAppUser->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonExerciceAppUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
