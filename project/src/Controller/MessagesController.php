<?php

namespace App\Controller;

use App\Entity\Cours;
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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessagesController extends AbstractController
{
    #[Route('/api/cours/{idCours}/messages', name: 'api_messages', methods: ['GET'])]
    public function getMessagesList(MessagesRepository $messagesRepository, UserRepository $userRepository, CoursRepository $coursRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $cours = $coursRepository->find($request->attributes->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $user = $userRepository->getUserFromToken();

        $messagesList = $messagesRepository->getMessagesFromUser($cours, $user);
        
        $jsonMessagesList = $serializer->serialize($messagesList, 'json', ['groups' => 'messages:read']);
        return new JsonResponse ($jsonMessagesList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours/{idCours}/messages/{idMessages}', name: 'api_messages_detail', methods: ['GET'])]
    public function getMessagesDetail(UserRepository $userRepository, CoursRepository $coursRepository, MessagesRepository $messagesRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $cours = $coursRepository->find($request->attributes->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $messages = $messagesRepository->find($request->attributes->get('idMessages'));
        if($messages->getCours() != $cours){
            return new JsonResponse(['error' => 'Ce message ne fait pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $jsonMessages = $serializer->serialize($messages, 'json', ['groups' => 'messages:read']);
        return new JsonResponse($jsonMessages, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours/{idCours}/messages/{idMessages}', name: 'api_cours_app_delete', methods: ['DELETE'])]
    public function deleteMessage(MessagesRepository $messagesRepository, CoursRepository $coursRepository, UserRepository $userRepository, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $user = $userRepository->getUserFromToken();
        $cours = $coursRepository->find($request->attributes->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$user) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $messages = $messagesRepository->find($request->attributes->get('idMessages'));
        if($messages->getCours() != $cours){
            return new JsonResponse(['error' => 'Ce message ne fait pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }


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

    #[Route('/api/cours/{idCours}/messages', name: 'api_messages_create', methods: ['POST'])]
    public function createMessage(CoursRepository $coursRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, ValidatorInterface $validator): JsonResponse
    {
        $cours = $coursRepository->find($request->attributes->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }
        
        $requestContent = $request->toArray();
        $messageContent = $requestContent['content'] ?? null;
        
        $sender = $userRepository->getUserFromToken();
        if($sender->getRoles()[0] == "ROLE_TEACHER"){
            $receiver = $cours->getStudent()->getUser();
        } else {
            $receiver = $cours->getTeacher()->getUser();
        }

        $message = $serializer->deserialize($request->getContent(), Messages::class, 'json');
        $message->setCours($cours);
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($messageContent);
        $message->setUnread($requestContent['unread'] ?? -1);

        // Validation des données
        $errors = $validator->validate($message);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', ['groups' => 'message:read']);
        $location = $urlGenerator->generate('api_messages_detail', ['idMessages' => $message->getId(), 'idCours' => $cours->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonMessage, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/cours/{idCours}/messages/{idMessages}', name: 'api_messages_modify', methods: ['PUT'])]
    public function modifyMessages(CoursRepository $coursRepository, UserRepository $userRepository, Request $request, MessagesRepository $messagesRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $cours = $coursRepository->find($request->attributes->get('idCours'));
        if($coursRepository->isUserFromCours($cours,$userRepository->getUserFromToken()) == false){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }
        $currentMessages = $messagesRepository->find($request->attributes->get('idMessages'));
        if($currentMessages->getCours() != $cours){
            return new JsonResponse(['error' => 'Ce message ne fait pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $updatedMessages = $serializer->deserialize($request->getContent(), Messages::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentMessages]);

        // Validation des données
        $errors = $validator->validate($updatedMessages);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedMessages);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
