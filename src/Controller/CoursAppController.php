<?php

namespace App\Controller;

use App\Entity\CoursApp;
use App\Repository\CoursAppRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CoursAppController extends AbstractController
{
    #[Route('/api/cours-app', name: 'api_cours_app', methods: ['GET'])]
    public function getCoursAppList(CoursAppRepository $coursAppRepository, SerializerInterface $serializer): JsonResponse
    {
        $coursAppList = $coursAppRepository->findAll();
        $jsonCoursAppList = $serializer->serialize($coursAppList, 'json');
        return new JsonResponse ($jsonCoursAppList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/cours-app/{id}', name: 'api_cours_app_detail', methods: ['GET'])]
    public function getInstrumentDetail(CoursApp $coursApp, SerializerInterface $serializer): JsonResponse
    {
        $jsonCoursApp = $serializer->serialize($coursApp, 'json', ['groups' => 'coursApp:read']);
        return new JsonResponse($jsonCoursApp, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
