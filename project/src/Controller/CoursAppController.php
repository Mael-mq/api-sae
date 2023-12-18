<?php

namespace App\Controller;

use App\Entity\CoursApp;
use App\Repository\CoursAppRepository;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoursAppController extends AbstractController
{
    #[Route('/api/cours-app', name: 'api_cours_app', methods: ['GET'])]
    public function getCoursAppList(CoursAppRepository $coursAppRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $offset = $request->get('offset', 1);
        $limit = $request->get('limit', 5);

        $coursAppList = $coursAppRepository->findAllWithPagination($offset, $limit);
        
        $jsonCoursAppList = $serializer->serialize($coursAppList, 'json', ['groups' => 'coursApp:read']);
        return new JsonResponse ($jsonCoursAppList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours-app/{id}', name: 'api_cours_app_detail', methods: ['GET'])]
    public function getCoursAppDetail(CoursApp $coursApp, SerializerInterface $serializer): JsonResponse
    {
        $jsonCoursApp = $serializer->serialize($coursApp, 'json', ['groups' => 'coursApp:read']);
        return new JsonResponse($jsonCoursApp, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    
    #[Route('/api/cours-app/{id}', name: 'api_cours_app_delete', methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function deleteCoursApp(CoursApp $coursApp, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($coursApp);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/cours-app', name: 'api_cours_app_create', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function createCoursApp(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, InstrumentRepository $instrumentRepository, ValidatorInterface $validator): JsonResponse
    {
        $coursApp = $serializer->deserialize($request->getContent(), CoursApp::class, 'json');
        
        // Récupération de l'ensemble des données envoyées sous forme de tableau 
        $content = $request->toArray();

        // Récupération de l'idInstrument. S'il n'est pas défini, alors on met -1 par défaut.
        $idInstrument = $content['idInstrument'] ?? -1;

        // On cherche l'instrument qui correspond et on l'assigne au cours.
        // Si "find" ne trouve pas l'instrument, alors null sera retourné.
        $coursApp->setInstrument($instrumentRepository->find($idInstrument));

        // Validation des données
        $errors = $validator->validate($coursApp);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $em->persist($coursApp);
        $em->flush();

        $jsonCoursApp = $serializer->serialize($coursApp, 'json', ['groups' => 'coursApp:read']);
        $location = $urlGenerator->generate('api_cours_app_detail', ['id' => $coursApp->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCoursApp, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/cours-app/{id}', name: 'api_cours_app_update', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas les droits suffisants.")]
    public function updateCoursApp(Request $request, SerializerInterface $serializer, CoursApp $currentCoursApp, EntityManagerInterface $em, InstrumentRepository $instrumentRepository, ValidatorInterface $validator): JsonResponse 
    {
        $updatedCoursApp = $serializer->deserialize($request->getContent(), 
                CoursApp::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCoursApp]);

        
        
        $content = $request->toArray();
        $idInstrument = $content['idInstrument'] ?? -1;
        $updatedCoursApp->setInstrument($instrumentRepository->find($idInstrument));
        
        // Validation des données
        $errors = $validator->validate($updatedCoursApp);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedCoursApp);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
