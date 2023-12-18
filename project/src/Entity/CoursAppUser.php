<?php

namespace App\Entity;

use App\Repository\CoursAppUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoursAppUserRepository::class)]
class CoursAppUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['coursAppUser:read'])]
    private ?int $id = null;

    #[Groups(['coursAppUser:read'])]
    #[ORM\ManyToOne(inversedBy: 'coursAppUsers')]
    private ?User $User = null;

    #[Groups(['coursAppUser:read'])]
    #[ORM\ManyToOne(inversedBy: 'coursAppUsers')]
    private ?CoursApp $CoursApp = null;

    #[Groups(['coursAppUser:read'])]
    #[ORM\Column]
    private ?bool $isFinished = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCoursApp(): ?CoursApp
    {
        return $this->CoursApp;
    }

    public function setCoursApp(?CoursApp $CoursApp): static
    {
        $this->CoursApp = $CoursApp;

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
