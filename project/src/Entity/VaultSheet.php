<?php

namespace App\Entity;

use App\Repository\VaultSheetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VaultSheetRepository::class)]
class VaultSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vaultSheet:read'])]
    private ?int $id = null;

    #[Groups(['vaultSheet:read'])]
    #[ORM\ManyToOne(inversedBy: 'vaultSheets')]
    private ?User $User = null;

    #[Groups(['vaultSheet:read'])]
    #[ORM\ManyToOne(inversedBy: 'vaultSheets')]
    private ?Sheet $Sheet = null;

    #[Groups(['vaultSheet:read'])]
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
