<?php

namespace App\Controller;

use App\Entity\Activities;
use App\Repository\ActivitiesRepository;
use App\Repository\CoursRepository;
use App\Repository\FilesRepository;
use App\Repository\SeanceRepository;
use App\Repository\SheetRepository;
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

class ActivitiesController extends AbstractController
{
    #[Route('/api/cours/{idCours}/activities', name: 'api_activities', methods: ['GET'])]
    public function getActivitiesList(UserRepository $userRepository, CoursRepository $coursRepository, ActivitiesRepository $activitiesRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $cours = $coursRepository->find($request->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $offset = $request->get('offset', 1);
        $limit = $request->get('limit', 5);
        
        $activitiesList = $activitiesRepository->findAllWithPagination($offset, $limit, $cours);

        
        $jsonActivitiesList = $serializer->serialize($activitiesList, 'json', ['groups' => 'activities:read']);
        return new JsonResponse ($jsonActivitiesList, Response::HTTP_OK, ['accept' => 'json'], true);
    }


    #[Route('/api/cours/{idCours}/activities/{idActivities}', name: 'api_activities_detail', methods: ['GET'])]
    public function getActivitiesDetail(UserRepository $userRepository, ActivitiesRepository $activitiesRepository, Request $request, CoursRepository $coursRepository, SerializerInterface $serializer): JsonResponse
    {
        $cours = $coursRepository->find($request->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }
        $activities = $activitiesRepository->find($request->attributes->get('idActivities'));
        if($activities->getCours() != $cours){
            return new JsonResponse(['error' => 'Cette activité ne fait pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $jsonActivities = $serializer->serialize($activities, 'json', ['groups' => 'activities:read']);
        return new JsonResponse($jsonActivities, Response::HTTP_OK, ['accept' => 'json'], true);
    }
    

    #[Route('/api/cours/{idCours}/activities/{idActivities}', name: 'api_activities_delete', methods: ['DELETE'])]
    public function deleteActivities(UserRepository $userRepository, ActivitiesRepository $activitiesRepository, CoursRepository $coursRepository, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $activities = $activitiesRepository->find($request->get('idActivities'));
        $cours = $coursRepository->find($request->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }
        $activities = $activitiesRepository->find($request->attributes->get('idActivities'));
        if($activities->getCours() != $cours){
            return new JsonResponse(['error' => 'Cette activité ne fait pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $files = $activities->getFiles();
        foreach($files as $file){
            $em->remove($file);
        }

        $em->remove($activities);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/cours/{idCours}/activities', name: 'api_activities_create', methods: ['POST'])]
    public function createActivities(UserRepository $userRepository, Request $request, CoursRepository $coursRepository, SerializerInterface $serializer, SeanceRepository $seanceRepository, SheetRepository $sheetRepository, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $cours = $coursRepository->find($request->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $activities = $serializer->deserialize($request->getContent(), Activities::class, 'json');
        
        $content = $request->toArray();
        $idSeance = $content['idSeance'] ?? -1;
        $idSheet = $content['idSheet'] ?? -1;

        $activities->setSeance($seanceRepository->find($idSeance));
        $activities->setSheet($sheetRepository->find($idSheet));
        $activities->setCours($cours);

        // Validation des données
        $errors = $validator->validate($activities);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($activities);
        $em->flush();

        $jsonActivities = $serializer->serialize($activities, 'json', ['groups' => 'activities:read']);
        $location = $urlGenerator->generate('api_activities_detail', ['idActivities' => $activities->getId(), 'idCours' => $cours->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonActivities, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/cours/{idCours}/activities/{idActivities}', name: 'api_activities_update', methods: ['PUT'])]
    public function updateActivities(CoursRepository $coursRepository, UserRepository $userRepository, Request $request, SheetRepository $sheetRepository, SeanceRepository $seanceRepository, SerializerInterface $serializer, ActivitiesRepository $activitiesRepository, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse 
    {
        $cours = $coursRepository->find($request->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }
        $activities = $activitiesRepository->find($request->attributes->get('idActivities'));
        if($activities->getCours() != $cours){
            return new JsonResponse(['error' => 'Cette activité ne fait pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $currentActivities = $activitiesRepository->find($request->get('idActivities'));

        $updatedActivities = $serializer->deserialize($request->getContent(), 
                Activities::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentActivities]);

        
        
        $content = $request->toArray();
        $idSheet = $content['idSheet'] ?? -1;
        if($idSheet != -1){
            $updatedActivities->setSheet($sheetRepository->find($idSheet));
        }

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
