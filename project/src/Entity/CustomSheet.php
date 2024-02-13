<?php

namespace App\Entity;

use App\Repository\CustomSheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomSheetRepository::class)]
class CustomSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customSheet:read'])]
    private ?int $id = null;

    #[Groups(['customSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    private ?string $title = null;

    #[Groups(['customSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La score key est obligatoire")]
    private ?string $scoreKey = null;

    #[Groups(['customSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $difficulty = null;

    #[Groups(['customSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $style = null;

    #[Groups(['customSheet:read'])]
    #[ORM\ManyToOne(inversedBy: 'customSheets')]
    #[Assert\NotBlank(message: "L'auteur est obligatoire ou n'existe pas")]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'CustomSheet', targetEntity: VaultCustomSheet::class)]
    private Collection $vaultCustomSheets;

    #[Groups(['customSheet:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La score key est obligatoire")]
    private ?string $instrument = null;

    public function __construct()
    {
        $this->vaultCustomSheets = new ArrayCollection();
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

    public function getScoreKey(): ?string
    {
        return $this->scoreKey;
    }

    public function setScoreKey(?string $scoreKey): static
    {
        $this->scoreKey = $scoreKey;

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

    public function getInstrument(): ?string
    {
        return $this->instrument;
    }

    public function setInstrument(?string $instrument): static
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, VaultCustomSheet>
     */
    public function getVaultCustomSheets(): Collection
    {
        return $this->vaultCustomSheets;
    }

    public function addVaultCustomSheet(VaultCustomSheet $vaultCustomSheet): static
    {
        if (!$this->vaultCustomSheets->contains($vaultCustomSheet)) {
            $this->vaultCustomSheets->add($vaultCustomSheet);
            $vaultCustomSheet->setCustomSheet($this);
        }

        return $this;
    }

    public function removeVaultCustomSheet(VaultCustomSheet $vaultCustomSheet): static
    {
        if ($this->vaultCustomSheets->removeElement($vaultCustomSheet)) {
            // set the owning side to null (unless already changed)
            if ($vaultCustomSheet->getCustomSheet() === $this) {
                $vaultCustomSheet->setCustomSheet(null);
            }
        }

        return $this;
    }
}
