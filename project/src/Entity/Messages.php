<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['messages:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['messages:read'])]
    #[Assert\NotBlank(message: "Le cours est obligatoire ou n'existe pas")]
    private ?Cours $Cours = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['messages:read'])]
    private ?string $content = null;

    #[ORM\ManyToOne]
    #[Groups(['messages:read'])]
    private ?User $Sender = null;

    #[ORM\ManyToOne]
    #[Groups(['messages:read'])]
    private ?User $Receiver = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['messages:read'])]
    private ?bool $unread = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->Sender;
    }

    public function setSender(?User $Sender): static
    {
        $this->Sender = $Sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->Receiver;
    }

    public function setReceiver(?User $Receiver): static
    {
        $this->Receiver = $Receiver;

        return $this;
    }

    public function isUnread(): ?bool
    {
        return $this->unread;
    }

    public function setUnread(?bool $unread): static
    {
        $this->unread = $unread;

        return $this;
    }
}
