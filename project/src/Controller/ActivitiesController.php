<?php

namespace App\Controller;

use App\Entity\Activities;
use App\Repository\ActivitiesRepository;
use App\Repository\SeanceRepository;
use App\Repository\SheetRepository;
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

class ActivitiesController extends AbstractController
{
    #[Route('/api/activities', name: 'api_activities', methods: ['GET'])]
    public function getActivitiesList(ActivitiesRepository $activitiesRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $activitiesList = $activitiesRepository->findAll();
        
        $jsonActivitiesList = $serializer->serialize($activitiesList, 'json', ['groups' => 'activities:read']);
        return new JsonResponse ($jsonActivitiesList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/activities/{id}', name: 'api_activities_detail', methods: ['GET'])]
    public function getActivitiesDetail(Activities $activities, SerializerInterface $serializer): JsonResponse
    {
        $jsonActivities = $serializer->serialize($activities, 'json', ['groups' => 'activities:read']);
        return new JsonResponse($jsonActivities, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/activities/{id}', name: 'api_activities_detail', methods: ['DELETE'])]
    public function deleteActivities(Activities $activities, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($activities);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/activities', name: 'api_activities_create', methods: ['POST'])]
    public function createActivities(Request $request, SerializerInterface $serializer, SeanceRepository $seanceRepository, SheetRepository $sheetRepository, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $activities = $serializer->deserialize($request->getContent(), Activities::class, 'json');
        
        $content = $request->toArray();
        $idSeance = $content['idSeance'] ?? -1;
        $idSheet = $content['idSheet'] ?? -1;

        $seance = $seanceRepository->find($idSeance);
        $activities->setSeance($seance);
        $activities->setSheet($sheetRepository->find($idSheet));
        $activities->setCours($seance->getCours());

        // Validation des données
        $errors = $validator->validate($activities);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($activities);
        $em->flush();

        $jsonActivities = $serializer->serialize($activities, 'json', ['groups' => 'activities:read']);
        $location = $urlGenerator->generate('api_activities_detail', ['id' => $activities->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonActivities, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/activities/{id}', name: 'api_activities_update', methods: ['PUT'])]
    public function updateActivities(Request $request, SheetRepository $sheetRepository, SeanceRepository $seanceRepository, SerializerInterface $serializer, Activities $currentActivities, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse 
    {
        $updatedActivities = $serializer->deserialize($request->getContent(), 
                Activities::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentActivities]);

        
        
        $content = $request->toArray();
        $idSeance = $content['idSeance'] ?? -1;
        $idSheet = $content['idSheet'] ?? -1;
        $seance = $seanceRepository->find($idSeance);
        $updatedActivities->setSeance($seance);
        $updatedActivities->setSheet($sheetRepository->find($idSheet));
        $updatedActivities->setCours($seance->getCours());
        
        // Validation des données
        $errors = $validator->validate($updatedActivities);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedActivities);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
