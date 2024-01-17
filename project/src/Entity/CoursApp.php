<?php

namespace App\Entity;

use App\Repository\CoursAppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursAppRepository::class)]
class CoursApp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["coursApp:read", 'coursAppUser:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["coursApp:read", 'coursAppUser:read'])]
    #[Assert\NotBlank(message: "Le titre du cours est obligatoire")]
    private ?string $Title = null;

    #[ORM\ManyToOne(inversedBy: 'coursApps')]
    #[Groups(["coursApp:read", 'coursAppUser:read'])]
    #[Assert\NotBlank(message: "L'instrument du cours est obligatoire ou n'existe pas")]
    private ?Instrument $Instrument = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["coursApp:read", 'coursAppUser:read'])]
    #[Assert\NotBlank(message: "La difficulté du cours est obligatoire")]
    private ?string $Difficulty = null;

    #[ORM\OneToMany(mappedBy: 'CoursApp', targetEntity: CoursAppUser::class)]
    private Collection $coursAppUsers;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: "La description est obligatoire")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    private ?string $content = null;

    public function __construct()
    {
        $this->coursAppUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): static
    {
        $this->Title = $Title;

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

    public function getDifficulty(): ?string
    {
        return $this->Difficulty;
    }

    public function setDifficulty(?string $Difficulty): static
    {
        $this->Difficulty = $Difficulty;

        return $this;
    }

    /**
     * @return Collection<int, CoursAppUser>
     */
    public function getCoursAppUsers(): Collection
    {
        return $this->coursAppUsers;
    }

    public function addCoursAppUser(CoursAppUser $coursAppUser): static
    {
        if (!$this->coursAppUsers->contains($coursAppUser)) {
            $this->coursAppUsers->add($coursAppUser);
            $coursAppUser->setCoursApp($this);
        }

        return $this;
    }

    public function removeCoursAppUser(CoursAppUser $coursAppUser): static
    {
        if ($this->coursAppUsers->removeElement($coursAppUser)) {
            // set the owning side to null (unless already changed)
            if ($coursAppUser->getCoursApp() === $this) {
                $coursAppUser->setCoursApp(null);
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
