<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\ActivitiesRepository;
use App\Repository\CoursRepository;
use App\Repository\InstrumentRepository;
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

class CoursController extends AbstractController
{
    #[Route('/api/cours', name: 'api_cours', methods: ['GET'])]
    public function getCoursList(UserRepository $userRepository, TeacherRepository $teacherRepository, StudentRepository $studentRepository, CoursRepository $coursRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->getUserFromToken();   
        if ($user->getRoles()[0] === "ROLE_TEACHER") {
            $teacher = $teacherRepository->findOneBy(['User'=>$user]);
            $coursList = $coursRepository->findBy(['Teacher'=>$teacher]);
        } else {
            $student = $studentRepository->findOneBy(['User'=>$user]);
            $coursList = $coursRepository->findBy(['Student'=>$student]);
        }
        
        $jsonCoursList = $serializer->serialize($coursList, 'json', ['groups' => 'cours:read']);
        return new JsonResponse ($jsonCoursList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours/{id}', name: 'api_cours_detail', methods: ['GET'])]
    public function getCoursDetail(UserRepository $userRepository, Cours $cours, SerializerInterface $serializer): JsonResponse
    {
        if($userRepository->getUserFromToken() != $cours->getStudent()->getUser() && $userRepository->getUserFromToken() != $cours->getTeacher()->getUser()){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $jsonCours = $serializer->serialize($cours, 'json', ['groups' => 'cours:read']);
        return new JsonResponse($jsonCours, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/cours/{id}', name: 'api_cours_delete', methods: ['DELETE'])]
    public function deleteCours(UserRepository $userRepository, Cours $cours, EntityManagerInterface $em): JsonResponse
    {
        if($userRepository->getUserFromToken() != $cours->getStudent()->getUser() && $userRepository->getUserFromToken() != $cours->getTeacher()->getUser()){
            return new JsonResponse(['error' => 'Vous ne faites pas partie de ce cours.'], Response::HTTP_FORBIDDEN);
        }

        $em->remove($cours);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/cours', name: 'api_cours_create', methods: ['POST'])]
    public function createCours(CoursRepository $coursRepository, InstrumentRepository $instrumentRepository, Request $request, StudentRepository $studentRepository, TeacherRepository $teacherRepository, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $content = $request->toArray();

        $idStudent = $content['idStudent'] ?? -1;
        $student = $studentRepository->find($idStudent);

        $idTeacher = $content['idTeacher'] ?? -1;
        $teacher = $teacherRepository->find($idTeacher);

        $idInstrument = $content['idInstrument'] ?? -1;
        
        $instrument = $instrumentRepository->find($idInstrument);

        $existingCours = $coursRepository->findOneBy(['Student'=>$student, 'Teacher'=>$teacher, 'Instrument'=>$instrument]);
        if ($existingCours) {
            return new JsonResponse("Ce cours existe déjà.", Response::HTTP_BAD_REQUEST);
        }

        $cours = $serializer->deserialize($request->getContent(), Cours::class, 'json');
        $cours->setStudent($student);
        $cours->setTeacher($teacher);
        $cours->setInstrument($instrument);

        $errors = $validator->validate($cours);
        if(count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($cours);
        $em->flush();

        $jsonCours = $serializer->serialize($cours, 'json', ['groups' => 'cours:read']);
        $location = $urlGenerator->generate('api_cours_detail', ['id' => $cours->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCours, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/cours/{id}', name: 'api_cours_modify', methods: ['PUT'])]
    public function modifyCours(Cours $cours, SerializerInterface $serializer, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $content = $request->toArray();
        if(isset($content['isPending']) && $content['isPending'] != null){
            $cours->setIsPending($content['isPending']);
        }

        $em->persist($cours);
        $em->flush();

        $jsonCours = $serializer->serialize($cours, 'json', ['groups' => 'cours:read']);
        return new JsonResponse($jsonCours, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
