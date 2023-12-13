<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}
