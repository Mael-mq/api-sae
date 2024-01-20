<?php

namespace App\Controller;

use App\Entity\Teacher;
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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeacherController extends AbstractController
{
    #[Route('/api/teachers', name: 'api_teachers', methods: ['GET'])]
    public function getTeacherList(TeacherRepository $teacherRepository, SerializerInterface $serializer): JsonResponse
    {
        $teacherList = $teacherRepository->findAll();
        $jsonTeacherList = $serializer->serialize($teacherList, 'json', ['groups' => 'teacher:read']);
        return new JsonResponse ($jsonTeacherList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/teachers/{id}', name: 'api_teachers_detail', methods: ['GET'])]
    public function getTeacherDetail(Teacher $teacher, SerializerInterface $serializer): JsonResponse
    {
        $jsonTeacher = $serializer->serialize($teacher, 'json', ['groups' => 'teacher:read']);
        return new JsonResponse ($jsonTeacher, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/teachers', name: 'api_teachers_create', methods: ['POST'])]
    public function createTeacher(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, TeacherRepository $teacherRepository, StudentRepository $studentRepository, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->toArray();
        $idUser = $content['idUser'] ?? -1;

        $user = $userRepository->findOneBy(['id' => $idUser]);

        $userRequest = $userRepository->getUserFromToken()->getUserIdentifier();
        if($userRequest != $user->getUserIdentifier()){
            return new JsonResponse(['error' => 'You are not allowed to create a teacher for this user.'], Response::HTTP_FORBIDDEN);
        }

        $existingTeacher = $teacherRepository->findOneBy(['User' => $user]);
        if ($existingTeacher) {
            return new JsonResponse(['error' => 'Teacher already exists.'], Response::HTTP_CONFLICT);
        }

        $existingStudent = $studentRepository->findOneBy(['User' => $user]);
        if ($existingStudent) {
            return new JsonResponse(['error' => 'User already registered as student.'], Response::HTTP_CONFLICT);
        }

        $user->setRoles(['ROLE_TEACHER']);

        $teacher = new Teacher();
        $teacher->setUser($user);

        // Validation des données
        $errors = $validator->validate($teacher);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($teacher);
        $em->flush();

        $jsonTeacher = $serializer->serialize($teacher, 'json', ['groups' => 'teacher:read']);
        $location = $urlGenerator->generate('api_teachers_detail', ['id' => $teacher->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonTeacher, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/teachers/{id}', name: 'api_teachers_update', methods: ['PUT'])]
    public function updateTeacher(Request $request, SerializerInterface $serializer, UserRepository $userRepository, Teacher $currentTeacher, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse 
    {
        if($currentTeacher->getUser()->getUserIdentifier() != $userRepository->getUserFromToken()->getUserIdentifier()){
            return new JsonResponse(['error' => 'You are not allowed to update this teacher.'], Response::HTTP_FORBIDDEN);
        }

        $updatedTeacher = $serializer->deserialize($request->getContent(), 
                Teacher::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentTeacher]);

        
        
        $content = $request->toArray();
        
        // Validation des données
        $errors = $validator->validate($updatedTeacher);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedTeacher);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
