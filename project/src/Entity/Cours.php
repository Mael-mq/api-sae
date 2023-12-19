<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cours:read'])]
    private ?int $id = null;

    #[Groups(['cours:read'])]
    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Student $Student = null;

    #[Groups(['cours:read'])]
    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Teacher $Teacher = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->Student;
    }

    public function setStudent(?Student $Student): static
    {
        $this->Student = $Student;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->Teacher;
    }

    public function setTeacher(?Teacher $Teacher): static
    {
        $this->Teacher = $Teacher;

        return $this;
    }
}
