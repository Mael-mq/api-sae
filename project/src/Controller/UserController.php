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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;


class UserController extends AbstractController
{
    #[Route('/api/user', name: 'api_user')]
    public function getUserInfos(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse ($jsonUser, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/user-seances', name: 'api_user')]
    public function getUserSeances(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        if($user->getRoles()[0] === "ROLE_STUDENT") {
            $userSeances = [];
            foreach($user->getStudents()[0]->getCours() as $cours) {
                $userSeances[] = $cours->getSeances();
            }
            $jsonSeances = $serializer->serialize($userSeances, 'json', ['groups' => 'seance:read']);
        }

        if($user->getRoles()[0] === "ROLE_TEACHER") {
            $userSeances = [];
            foreach($user->getTeachers()[0]->getCours() as $cours) {
                $userSeances[] = $cours->getSeances();
            }
            $jsonSeances = $serializer->serialize($userSeances, 'json', ['groups' => 'seance:read']);
        }

        return new JsonResponse ($jsonSeances, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/user/{idUser}', name: 'api_user_modify', methods: ['PUT'])]
    public function modifyUser(Request $request, UserRepository $userRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $userRequest = $userRepository->find($request->get('idUser'));
        $currentUser = $userRepository->getUserFromToken();

        if($userRequest !== $currentUser) {
            return new JsonResponse("Vous n'avez pas les droits suffisants.", Response::HTTP_FORBIDDEN);
        }

        $content = $request->toArray();

        if(isset($content['id']) || isset($content['email']) || isset($content['roles']) || isset($content['password'])) {
            return new JsonResponse("Vous ne pouvez pas modifier ces informations", Response::HTTP_FORBIDDEN);
        }
        $updatedUser = $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);

        // Validation des données
        $errors = $validator->validate($updatedUser);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/register', name: 'api_register')]
    public function registerUser(UserPasswordHasherInterface $userPasswordHasherInterface, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->toArray();
        $email = $content['email'] ?? -1;

        $emailConstraint = new EmailConstraint();
        $emailConstraint->message = 'Email invalide';
        $errorsEmail = $validator->validate($email,$emailConstraint);
        if(count($errorsEmail) > 0) {
            return new JsonResponse($serializer->serialize($errorsEmail, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $role = $content['role'] ?? -1;
        $password = $content['password'] ?? -1;
        $nom = $content['nom'] ?? -1;
        $prenom = $content['prenom'] ?? -1;
        $gender = $content['gender'] ?? -1;

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($userPasswordHasherInterface->hashPassword($user, $password));
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setGender($gender);

        if($role === "student") {
            $user->setRoles(["ROLE_STUDENT", "ROLE_USER"]);
        }
        if($role === "teacher") {
            $user->setRoles(["ROLE_TEACHER", "ROLE_USER"]);
        }

        $errors = $validator->validate($user);
        if(count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($user);
        $em->flush();

        if($role === "student") {
            $student = new Student();
            $student->setUser($user);
            $em->persist($student);
            $em->flush();
        }
        if($role === "teacher") {
            $teacher = new Teacher();
            $teacher->setUser($user);
            $em->persist($teacher);
            $em->flush();
        }

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, [], true);
    }

    
}
