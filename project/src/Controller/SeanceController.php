<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Seance;
use App\Repository\CoursRepository;
use App\Repository\SeanceRepository;
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

class SeanceController extends AbstractController
{
    #[Route('/api/cours/{idCours}/seances', name: 'api_seances', methods: ['GET'])]
    public function getSeanceList(SeanceRepository $seanceRepository, CoursRepository $coursRepository, UserRepository $userRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $cours = $coursRepository->find($request->attributes->get('idCours'));

        $seanceList = $seanceRepository->findBy(['Cours'=>$cours]);
        
        $jsonSeanceList = $serializer->serialize($seanceList, 'json', ['groups' => 'seance:read']);
        return new JsonResponse ($jsonSeanceList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours/{idCours}/seances/{idSeance}', name: 'api_seances_detail', methods: ['GET'])]
    public function getSeanceDetail(SeanceRepository $seanceRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $seance = $seanceRepository->find($request->attributes->get('idSeance'));

        $jsonCoursApp = $serializer->serialize($seance, 'json', ['groups' => 'seance:read']);
        return new JsonResponse($jsonCoursApp, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    
    #[Route('/api/cours/{idCours}/seances/{idSeance}', name: 'api_seances_delete', methods: ['DELETE'])]
    public function deleteSeance(SeanceRepository $seanceRepository, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $seance = $seanceRepository->find($request->attributes->get('idSeance'));

        $em->remove($seance);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/cours/{idCours}/seances', name: 'api_seances_create', methods: ['POST'])]
    public function createSeance(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, CoursRepository $coursRepository, ValidatorInterface $validator): JsonResponse
    {
        $seance = $serializer->deserialize($request->getContent(), Seance::class, 'json');
        
        $cours = $coursRepository->find($request->attributes->get('idCours'));
        $seance->setCours($cours);

        // Validation des données
        $errors = $validator->validate($seance);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($seance);
        $em->flush();

        $jsonSeance = $serializer->serialize($seance, 'json', ['groups' => 'seance:read']);
        $location = $urlGenerator->generate('api_seances_detail', ['idSeance' => $seance->getId(), 'idCours' => $seance->getCours()->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonSeance, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/cours/{idCours}/seances/{idSeance}', name: 'api_seances_update', methods: ['PUT'])]
    public function updateSeance(Request $request, SeanceRepository $seanceRepository, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, CoursRepository $coursRepository): JsonResponse 
    {
        $currentSeance = $seanceRepository->find($request->attributes->get('idSeance'));

        $updatedSeance = $serializer->deserialize($request->getContent(), 
                Seance::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentSeance]);

        $updatedSeance->setCours($currentSeance->getCours());
        
        // Validation des données
        $errors = $validator->validate($updatedSeance);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedSeance);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
