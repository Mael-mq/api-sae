<?php

namespace App\Entity;

use App\Repository\SheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SheetRepository::class)]
class Sheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sheet:read', 'vaultSheet:read', 'activities:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sheet:read', 'vaultSheet:read'])]
    #[Assert\NotBlank(message: "Le titre de la partition est obligatoire")]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'sheets')]
    #[Groups(['sheet:read', 'vaultSheet:read'])]
    #[Assert\NotBlank(message: "L'instrument est obligatoire ou n'existe pas")]
    private ?Instrument $Instrument = null;

    #[ORM\OneToMany(mappedBy: 'Sheet', targetEntity: VaultSheet::class)]
    private Collection $vaultSheets;

    #[Groups(['sheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La clé de la partition est obligatoire")]
    private ?string $scoreKey = null;

    #[Groups(['sheet:read', 'vaultSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'auteur est obligatoire")]
    private ?string $author = null;

    #[ORM\OneToMany(mappedBy: 'Sheet', targetEntity: Activities::class)]
    private Collection $activities;

    #[Groups(['sheet:read', 'vaultSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La difficulté est obligatoire")]
    private ?string $difficulty = null;

    #[Groups(['sheet:read', 'vaultSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le style est obligatoire")]
    private ?string $style = null;

    public function __construct()
    {
        $this->vaultSheets = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

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

    public function getInstrument(): ?Instrument
    {
        return $this->Instrument;
    }

    public function setInstrument(?Instrument $Instrument): static
    {
        $this->Instrument = $Instrument;

        return $this;
    }

    /**
     * @return Collection<int, VaultSheet>
     */
    public function getVaultSheets(): Collection
    {
        return $this->vaultSheets;
    }

    public function addVaultSheet(VaultSheet $vaultSheet): static
    {
        if (!$this->vaultSheets->contains($vaultSheet)) {
            $this->vaultSheets->add($vaultSheet);
            $vaultSheet->setSheet($this);
        }

        return $this;
    }

    public function removeVaultSheet(VaultSheet $vaultSheet): static
    {
        if ($this->vaultSheets->removeElement($vaultSheet)) {
            // set the owning side to null (unless already changed)
            if ($vaultSheet->getSheet() === $this) {
                $vaultSheet->setSheet(null);
            }
        }

        return $this;
    }

    public function getScoreKey(): ?string
    {
        return $this->scoreKey;
    }

    public function setScoreKey(?string $scoreKey): static
    {
        $this->scoreKey = $scoreKey;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

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
            $activity->setSheet($this);
        }

        return $this;
    }

    public function removeActivity(Activities $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getSheet() === $this) {
                $activity->setSheet(null);
            }
        }

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): static
    {
        $this->style = $style;

        return $this;
    }
}
