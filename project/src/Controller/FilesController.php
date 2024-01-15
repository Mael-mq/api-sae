<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Files;
use App\Repository\CoursRepository;
use App\Repository\FilesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FilesController extends AbstractController
{
    #[Route('/api/files', name: 'api_files_list', methods: ['GET'])]
    public function getFilesList(FilesRepository $filesRepository, CoursRepository $coursRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $content = $request->toArray();
        $idCours = $content['idCours'] ?? -1;
        $cours = $coursRepository->find($idCours);

        $filesList = $filesRepository->findBy(['Cours'=>$cours]);
        
        $jsonFilesList = $serializer->serialize($filesList, 'json', ['groups' => 'files:read']);
        return new JsonResponse ($jsonFilesList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/files/{id}', name: 'api_files_detail', methods: ['GET'])]
    public function getFilesDetail(Files $files, SerializerInterface $serializer): JsonResponse
    {
        $jsonFiles = $serializer->serialize($files, 'json', ['groups' => 'files:read']);
        return new JsonResponse($jsonFiles, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/files/{id}', name: 'api_files_delete', methods: ['DELETE'])]
    public function deleteFiles(Files $files, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($files);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/files', name: 'api_upload_files', methods: ['POST'])]
    public function uploadFiles(Request $request, CoursRepository $coursRepository, EntityManagerInterface $em, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator)
    {
        $files = new Files();

        $idCours = $request->request->get('idCours') ?? -1;

        $files->setCours($coursRepository->find($idCours));

        $file = $request->files->get('file');

        $files->setUploadedFile($file);

        $em->persist($files);
        $em->flush();

        $jsonFiles = $serializer->serialize($files, 'json', ['groups' => 'files:read']);
        $location = $urlGenerator->generate('api_cours_app_detail', ['id' => $files->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonFiles, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
