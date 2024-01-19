<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/api/user', name: 'api_user')]
    public function getUserInfos(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse ($jsonUser, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/register', name: 'api_register')]
    public function registerUser(UserPasswordHasherInterface $userPasswordHasherInterface, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->toArray();
        $email = $content['email'] ?? -1;
        $role = $content['role'] ?? -1;
        $password = $content['password'] ?? -1;
        $nom = $content['nom'] ?? -1;
        $prenom = $content['prenom'] ?? -1;
        $gender = $content['gender'] ?? -1;

        $user = new User();
        $user->setEmail($email);
        if($role === "student") {
            $user->setRoles(["ROLE_STUDENT", "ROLE_USER"]);
            $student = new Student();
            $student->setUser($user);
            $em->persist($student);
            $em->flush();
        }
        if($role === "teacher") {
            $user->setRoles(["ROLE_TEACHER", "ROLE_USER"]);
            $teacher = new Teacher();
            $teacher->setUser($user);
            $em->persist($teacher);
            $em->flush();
        }
        $user->setPassword($userPasswordHasherInterface->hashPassword($user, $password));
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setGender($gender);

        $errors = $validator->validate($user);
        if(count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($user);
        $em->flush();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, [], true);
    }
}
