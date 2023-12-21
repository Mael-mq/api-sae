<?php

namespace App\Entity;

use App\Repository\VaultSheetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VaultSheetRepository::class)]
class VaultSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vaultSheets')]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'vaultSheets')]
    private ?Sheet $Sheet = null;

    #[ORM\Column]
    private ?bool $isFavorite = null;

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

    public function getSheet(): ?Sheet
    {
        return $this->Sheet;
    }

    public function setSheet(?Sheet $Sheet): static
    {
        $this->Sheet = $Sheet;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }
}
