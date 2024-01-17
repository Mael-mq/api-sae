<?php

namespace App\Entity;

use App\Repository\ActivitiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActivitiesRepository::class)]
class Activities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activities:read', 'cours:read'])]
    private ?int $id = null;

    #[Groups(['activities:read', 'cours:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    private ?string $title = null;

    #[Groups(['activities:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    private ?string $content = null;

    #[Groups(['activities:read'])]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Assert\NotBlank(message: "Le cours est obligatoire ou n'existe pas")]
    private ?Cours $Cours = null;

    #[Groups(['activities:read'])]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Assert\NotBlank(message: "La séance est obligatoire ou n'existe pas")]
    private ?Seance $Seance = null;

    #[Groups(['activities:read'])]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Sheet $Sheet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->Cours;
    }

    public function setCours(?Cours $Cours): static
    {
        $this->Cours = $Cours;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->Seance;
    }

    public function setSeance(?Seance $Seance): static
    {
        $this->Seance = $Seance;

        return $this;
    }

    public function getSheet(): ?Sheet
    {
        return $this->Sheet;
    }

    public function setSheet(?Sheet $Sheet): static
    {
        $this->Sheet = $Sheet;

        return $this;
    }
}
