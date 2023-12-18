<?php

namespace App\Controller;

use App\Entity\ExerciceApp;
use App\Repository\ExerciceAppRepository;
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

class ExerciceAppController extends AbstractController
{
    #[Route('/api/exercice-app', name: 'api_exercice_app', methods: ['GET'])]
    public function getExerciceAppList(ExerciceAppRepository $exerciceAppRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $offset = $request->get('offset', 1);
        $limit = $request->get('limit', 5);

        $exerciceAppList = $exerciceAppRepository->findAllWithPagination($offset, $limit);
        
        $jsonExerciceAppList = $serializer->serialize($exerciceAppList, 'json', ['groups' => 'exerciceApp:read']);
        return new JsonResponse ($jsonExerciceAppList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/exercice-app/{id}', name: 'api_exercice_app_detail', methods: ['GET'])]
    public function getExerciceAppDetail(ExerciceApp $exerciceApp, SerializerInterface $serializer): JsonResponse
    {
        $jsonExerciceApp = $serializer->serialize($exerciceApp, 'json', ['groups' => 'exerciceApp:read']);
        return new JsonResponse($jsonExerciceApp, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    
    #[Route('/api/exercice-app/{id}', name: 'api_exercice_app_delete', methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function deleteExerciceApp(ExerciceApp $exerciceApp, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($exerciceApp);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/exercice-app', name: 'api_exercice_app_create', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function createExerciceApp(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $exerciceApp = $serializer->deserialize($request->getContent(), ExerciceApp::class, 'json');

        // Validation des données
        $errors = $validator->validate($exerciceApp);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($exerciceApp);
        $em->flush();

        $jsonExerciceApp = $serializer->serialize($exerciceApp, 'json', ['groups' => 'exerciceApp:read']);
        $location = $urlGenerator->generate('api_cours_app_detail', ['id' => $exerciceApp->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonExerciceApp, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/exercice-app/{id}', name: 'api_cours_app_update', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function updateExerciceApp(Request $request, SerializerInterface $serializer, ExerciceApp $currentExerciceApp, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse 
    {
        $updatedCoursApp = $serializer->deserialize($request->getContent(), ExerciceApp::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentExerciceApp]);
        
        // Validation des données
        $errors = $validator->validate($updatedCoursApp);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedCoursApp);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
