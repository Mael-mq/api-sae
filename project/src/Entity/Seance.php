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
    #[Groups(['seance:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[Groups(['seance:read'])]
    #[Assert\NotBlank(message: "Le cours est obligatoire ou n'existe pas")]
    private ?Cours $Cours = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['seance:read'])]
    #[Assert\NotBlank(message: "Date de début obligatoire")]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['seance:read'])]
    #[Assert\NotBlank(message: "Date de fin obligatoire")]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\OneToMany(mappedBy: 'Seance', targetEntity: Activities::class)]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
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
}
