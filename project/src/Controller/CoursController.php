<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/api/cours/{id}', name: 'api_cours_delete', methods: ['DELETE'])]
    public function deleteCours(Cours $cours, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($cours);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/cours', name: 'api_cours_create', methods: ['POST'])]
    public function createCours(CoursRepository $coursRepository, Request $request, StudentRepository $studentRepository, TeacherRepository $teacherRepository, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $content = $request->toArray();

        $idStudent = $content['idStudent'] ?? -1;
        $student = $studentRepository->find($idStudent);

        $idTeacher = $content['idTeacher'] ?? -1;
        $teacher = $teacherRepository->find($idTeacher);

        $existingCours = $coursRepository->findOneBy(['Student'=>$student, 'Teacher'=>$teacher]);
        if ($existingCours) {
            return new JsonResponse("Ce cours existe déjà.", Response::HTTP_BAD_REQUEST);
        }

        $cours = new Cours();
        $cours->setStudent($student);
        $cours->setTeacher($teacher);

        $errors = $validator->validate($cours);
        if(count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($cours);
        $em->flush();

        $jsonCours = $serializer->serialize($cours, 'json', ['groups' => 'cours:read']);
        $location = $urlGenerator->generate('api_cours_detail', ['id' => $cours->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCours, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}