<?php

namespace App\Entity;

use App\Repository\ActivitiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivitiesRepository::class)]
class Activities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Cours $Cours = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Seance $Seance = null;

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
