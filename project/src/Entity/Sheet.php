<?php

namespace App\Entity;

use App\Repository\SheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SheetRepository::class)]
class Sheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sheet:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sheet:read'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'sheets')]
    #[Groups(['sheet:read'])]
    private ?Instrument $Instrument = null;

    #[ORM\OneToMany(mappedBy: 'Sheet', targetEntity: VaultSheet::class)]
    private Collection $vaultSheets;

    public function __construct()
    {
        $this->vaultSheets = new ArrayCollection();
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
}
