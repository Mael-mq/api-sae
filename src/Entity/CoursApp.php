<?php

namespace App\Entity;

use App\Repository\CoursAppRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursAppRepository::class)]
class CoursApp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["coursApp:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["coursApp:read"])]
    #[Assert\NotBlank(message: "Le titre du cours est obligatoire")]
    private ?string $Title = null;

    #[ORM\ManyToOne(inversedBy: 'coursApps')]
    #[Groups(["coursApp:read"])]
    #[Assert\NotBlank(message: "L'instrument du cours est obligatoire ou n'existe pas")]
    private ?Instrument $Instrument = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["coursApp:read"])]
    #[Assert\NotBlank(message: "La difficulté du cours est obligatoire")]
    private ?string $Difficulty = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->Instrument;
    }

    public function setInstrument(?Instrument $Instrument): static
    {
        $this->Instrument = $Instrument;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->Difficulty;
    }

    public function setDifficulty(?string $Difficulty): static
    {
        $this->Difficulty = $Difficulty;

        return $this;
    }
}
