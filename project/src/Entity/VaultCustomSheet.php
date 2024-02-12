<?php

namespace App\Entity;

use App\Repository\VaultCustomSheetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VaultCustomSheetRepository::class)]
class VaultCustomSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customSheet:read'])]
    private ?int $id = null;

    #[Groups(['customSheet:read'])]
    #[ORM\ManyToOne(inversedBy: 'vaultCustomSheets')]
    #[Assert\NotBlank(message: "L'utilisateur est obligatoire ou n'existe pas.")]
    private ?User $User = null;

    #[Groups(['customSheet:read'])]
    #[ORM\ManyToOne(inversedBy: 'vaultCustomSheets')]
    #[Assert\NotBlank(message: "La custom sheet est obligatoire ou n'existe pas.")]
    private ?CustomSheet $CustomSheet = null;

    #[Groups(['customSheet:read'])]
    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "IsFavorite est obligatoire")]
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

    public function getCustomSheet(): ?CustomSheet
    {
        return $this->CustomSheet;
    }

    public function setCustomSheet(?CustomSheet $CustomSheet): static
    {
        $this->CustomSheet = $CustomSheet;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(?bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }
}
