<?php

namespace App\Controller;

use App\Entity\CoursAppUser;
use App\Repository\CoursAppRepository;
use App\Repository\CoursAppUserRepository;
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

class CoursAppUserController extends AbstractController
{
    #[Route('/api/cours-app-user', name: 'api_cours_app_user', methods: ['GET'])]
    public function getCoursAppUserList(UserRepository $userRepository, CoursAppUserRepository $coursAppUserRepository, SerializerInterface $serializer): JsonResponse
    {
        $coursAppUserList = $coursAppUserRepository->findBy(['User'=>$userRepository->getUserFromToken()]);
        
        $jsonCoursAppUserList = $serializer->serialize($coursAppUserList, 'json', ['groups' => 'coursAppUser:read']);
        return new JsonResponse ($jsonCoursAppUserList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours-app-user/{id}', name: 'api_cours_app_user_detail', methods: ['GET'])]
    public function getCoursAppDetail(UserRepository $userRepository, CoursAppUser $coursAppUser, SerializerInterface $serializer): JsonResponse
    {

        $userRequest = $coursAppUser->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $jsonCoursAppUser = $serializer->serialize($coursAppUser, 'json', ['groups' => 'coursAppUser:read']);
        return new JsonResponse($jsonCoursAppUser, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours-app-user/{id}', name: 'api_cours_app_user_modify', methods: ['PUT'])]
    public function modifyCoursAppUser(Request $request, UserRepository $userRepository, CoursAppUser $currentCoursAppUser, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $userRequest = $currentCoursAppUser->getUser()->getUserIdentifier();
        $user = $userRepository->getUserFromToken()->getUserIdentifier();

        if($userRequest != $user) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $updatedCoursAppUser = $serializer->deserialize($request->getContent(), CoursAppUser::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCoursAppUser]);
        
        $updatedCoursAppUser->setCoursApp($currentCoursAppUser->getCoursApp());
        $updatedCoursAppUser->setUser($currentCoursAppUser->getUser());

        // Validation des données
        $errors = $validator->validate($updatedCoursAppUser);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedCoursAppUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/cours-app-user', name: 'api_cours_app_create', methods: ['POST'])]
    public function createCoursAppUser(Request $request, UserRepository $userRepository, CoursAppRepository $coursAppRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $coursAppUser = new CoursAppUser();
        $coursAppUser->setUser($user);

        $content = $request->toArray();
        $idCoursApp = $content['idCoursApp'] ?? -1;
        $coursApp = $coursAppRepository->find($idCoursApp);
        $coursAppUser->setCoursApp($coursApp);

        $coursAppUser->setIsFinished(false);

        $errors = $validator->validate($coursAppUser);
        if(count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($coursAppUser);
        $em->flush();
        

        $jsonCoursAppUser = $serializer->serialize($coursAppUser, 'json', ['groups' => 'coursAppUser:read']);
        $location = $urlGenerator->generate('api_cours_app_user_detail', ['id' => $coursAppUser->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCoursAppUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
