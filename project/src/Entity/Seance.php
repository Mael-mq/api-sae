<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['seance:read', 'activities:read', 'cours:read', 'files:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[Groups(['seance:read'])]
    #[Assert\NotBlank(message: "Le cours est obligatoire ou n'existe pas")]
    private ?Cours $Cours = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['seance:read', 'cours:read'])]
    #[Assert\NotBlank(message: "Date de début obligatoire")]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['seance:read', 'cours:read'])]
    #[Assert\NotBlank(message: "Date de fin obligatoire")]
    private ?\DateTimeInterface $endAt = null;

    #[Groups(['cours:read'])]
    #[ORM\OneToMany(mappedBy: 'Seance', targetEntity: Activities::class)]
    private Collection $activities;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['seance:read', 'cours:read'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'Seance', targetEntity: Files::class)]
    private Collection $files;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection<int, Activities>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activities $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setSeance($this);
        }

        return $this;
    }

    public function removeActivity(Activities $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getSeance() === $this) {
                $activity->setSeance(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setSeance($this);
        }

        return $this;
    }

    public function removeFile(Files $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getSeance() === $this) {
                $file->setSeance(null);
            }
        }

        return $this;
    }
}
