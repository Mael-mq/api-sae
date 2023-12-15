<?php

namespace App\Controller;

use App\Entity\Student;
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

class StudentController extends AbstractController
{
    #[Route('/api/students', name: 'api_students', methods: ['GET'])]
    public function getStudentList(StudentRepository $studentRepository, SerializerInterface $serializer): JsonResponse
    {
        $studentList = $studentRepository->findAll();
        $jsonStudentList = $serializer->serialize($studentList, 'json', ['groups' => 'student:read']);
        return new JsonResponse ($jsonStudentList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/students/{id}', name: 'api_students_detail', methods: ['GET'])]
    public function getStudentDetail(Student $student, SerializerInterface $serializer): JsonResponse
    {
        $jsonStudent = $serializer->serialize($student, 'json', ['groups' => 'student:read']);
        return new JsonResponse ($jsonStudent, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/students', name: 'api_students_create', methods: ['POST'])]
    public function createStudent(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, StudentRepository $studentRepository, TeacherRepository $teacherRepository, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->toArray();
        $idUser = $content['idUser'] ?? -1;

        $user = $userRepository->findOneBy(['id' => $idUser]);

        $userRequest = $userRepository->getUserFromToken()->getUserIdentifier();
        if($userRequest != $user->getUserIdentifier()){
            return new JsonResponse(['error' => 'You are not allowed to create a student for this user.'], Response::HTTP_FORBIDDEN);
        }

        $existingStudent = $studentRepository->findOneBy(['User' => $user]);
        if ($existingStudent) {
            return new JsonResponse(['error' => 'Student already exists.'], Response::HTTP_CONFLICT);
        }

        $existingTeacher = $teacherRepository->findOneBy(['User' => $user]);
        if ($existingTeacher) {
            return new JsonResponse(['error' => 'User already registered as teacher.'], Response::HTTP_CONFLICT);
        }

        $user->setRoles(['ROLE_STUDENT']);

        $student = new Student();
        $student->setUser($user);

        // Validation des données
        $errors = $validator->validate($student);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($student);
        $em->flush();

        $jsonCoursApp = $serializer->serialize($student, 'json', ['groups' => 'student:read']);
        $location = $urlGenerator->generate('api_students_detail', ['id' => $student->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCoursApp, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
