<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/api/user', name: 'api_user')]
    public function getUserInfos(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse ($jsonUser, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /* #[Route('/api/register', name: 'api_register')]
    public function registerUser(UserPasswordHasherInterface $userPasswordHasherInterface, Request $request, Serializer $serializer): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $content = $request->toArray();
        $email = $content['email'] ?? -1;
        $roles = $content['roles'] ?? -1;

    
    } */
}
