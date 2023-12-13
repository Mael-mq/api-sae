<?php

namespace App\Entity;

use App\Repository\ExerciceAppUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExerciceAppUserRepository::class)]
class ExerciceAppUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exerciceAppUser:read'])]
    private ?int $id = null;

    #[Groups(['exerciceAppUser:read'])]
    #[ORM\ManyToOne(inversedBy: 'exerciceAppUsers')]
    private ?ExerciceApp $ExerciceApp = null;

    #[Groups(['exerciceAppUser:read'])]
    #[ORM\ManyToOne(inversedBy: 'exerciceAppUsers')]
    private ?User $User = null;

    #[Groups(['exerciceAppUser:read'])]
    #[ORM\Column]
    private ?bool $isFinished = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExerciceApp(): ?ExerciceApp
    {
        return $this->ExerciceApp;
    }

    public function setExerciceApp(?ExerciceApp $ExerciceApp): static
    {
        $this->ExerciceApp = $ExerciceApp;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function isIsFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): static
    {
        $this->isFinished = $isFinished;

        return $this;
    }
}
