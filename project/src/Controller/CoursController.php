<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CoursController extends AbstractController
{
    #[Route('/api/cours', name: 'api_cours', methods: ['GET'])]
    public function getCoursList(UserRepository $userRepository, TeacherRepository $teacherRepository, StudentRepository $studentRepository, CoursRepository $coursRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->getUserFromToken();
        if ($user->getRoles()[0] === "ROLE_TEACHER") {
            $teacher = $teacherRepository->findOneBy(['User'=>$user]);
            $coursList = $coursRepository->findBy(['Teacher'=>$teacher]);
        } else {
            $student = $studentRepository->findOneBy(['User'=>$user]);
            $coursList = $coursRepository->findBy(['Student'=>$student]);
        }
        
        $jsonCoursList = $serializer->serialize($coursList, 'json', ['groups' => 'cours:read']);
        return new JsonResponse ($jsonCoursList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours/{id}', name: 'api_cours_detail', methods: ['GET'])]
    public function getCoursDetail(Cours $cours, SerializerInterface $serializer): JsonResponse
    {
        

        $jsonCours = $serializer->serialize($cours, 'json', ['groups' => 'cours:read']);
        return new JsonResponse($jsonCours, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
