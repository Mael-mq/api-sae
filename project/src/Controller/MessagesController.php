<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Repository\CoursRepository;
use App\Repository\MessagesRepository;
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

class MessagesController extends AbstractController
{
    #[Route('/api/messages', name: 'api_messages', methods: ['GET'])]
    public function getMessagesList(MessagesRepository $messagesRepository, UserRepository $userRepository, CoursRepository $coursRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $content = $request->toArray();
        $idCours = $content['idCours'] ?? -1;
        if ($idCours == -1) {
            return new JsonResponse(null, JsonResponse::HTTP_BAD_REQUEST);
        }
        $cours = $coursRepository->find($idCours);
        $user = $userRepository->getUserFromToken();

        $messagesList = $messagesRepository->getMessagesFromUser($cours, $user);
        
        $jsonMessagesList = $serializer->serialize($messagesList, 'json', ['groups' => 'messages:read']);
        return new JsonResponse ($jsonMessagesList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/messages/{id}', name: 'api_messages_detail', methods: ['GET'])]
    public function getMessagesDetail(Messages $messages, SerializerInterface $serializer): JsonResponse
    {
        $jsonMessages = $serializer->serialize($messages, 'json', ['groups' => 'messages:read']);
        return new JsonResponse($jsonMessages, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/messages/{id}', name: 'api_cours_app_delete', methods: ['DELETE'])]
    public function deleteMessage(Messages $messages, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $userRepository->getUserFromToken();

        if($messages->getSender() != $user && $messages->getReceiver() != $user) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }
        
        if($messages->getSender() == $user) {
            $messages->setSender(null);
        }

        if($messages->getReceiver() == $user) {
            $messages->setReceiver(null);
        }

        if($messages->getSender() == null && $messages->getReceiver() == null) {
            $em->remove($messages);
        }

        $em->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/messages', name: 'api_messages_create', methods: ['POST'])]
    public function createMessage(CoursRepository $coursRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $requestContent = $request->toArray();

        $idCours = $requestContent['idCours'] ?? -1;

        $messageContent = $requestContent['content'] ?? null;

        $cours = $coursRepository->find($idCours);
        
        $sender = $userRepository->getUserFromToken();
        if($sender->getRoles()[0] == "ROLE_TEACHER"){
            $receiver = $cours->getStudent()->getUser();
        } else {
            $receiver = $cours->getTeacher()->getUser();
        }

        $message = new Messages();
        $message->setCours($cours);
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($messageContent);

        // Validation des données
        $errors = $validator->validate($message);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', ['groups' => 'message:read']);
        $location = $urlGenerator->generate('api_messages_detail', ['id' => $message->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonMessage, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
