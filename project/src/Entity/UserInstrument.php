<?php

namespace App\Entity;

use App\Repository\UserInstrumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserInstrumentRepository::class)]
class UserInstrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['userInstrument:read', 'teacher:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInstruments')]
    #[Groups(['userInstrument:read', 'teacher:read'])]
    #[Assert\NotBlank(message: "L'instrument est obligatoire ou n'existe pas")]
    private ?Instrument $Instrument = null;

    #[ORM\ManyToOne(inversedBy: 'userInstruments')]
    #[Groups(['userInstrument:read'])]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
