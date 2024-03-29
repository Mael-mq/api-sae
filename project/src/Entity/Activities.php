<?php

namespace App\Entity;

use App\Repository\ActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActivitiesRepository::class)]
class Activities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activities:read', 'cours:read', 'seance:read'])]
    private ?int $id = null;

    #[Groups(['activities:read', 'cours:read', 'seance:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    private ?string $title = null;

    #[Groups(['activities:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    private ?string $content = null;

    #[Groups(['activities:read'])]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Assert\NotBlank(message: "Le cours est obligatoire ou n'existe pas")]
    private ?Cours $Cours = null;

    #[Groups(['activities:read'])]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Assert\NotBlank(message: "La séance est obligatoire ou n'existe pas")]
    private ?Seance $Seance = null;

    #[Groups(['activities:read'])]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Sheet $Sheet = null;

    #[Groups(['activities:read', 'cours:read'])]
    #[Assert\NotBlank(message: "Le statut est obligatoire")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[Groups(['activities:read'])]
    #[ORM\OneToMany(mappedBy: 'Activities', targetEntity: Files::class)]
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
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

    public function getSeance(): ?Seance
    {
        return $this->Seance;
    }

    public function setSeance(?Seance $Seance): static
    {
        $this->Seance = $Seance;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setActivities($this);
        }

        return $this;
    }

    public function removeFile(Files $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getActivities() === $this) {
                $file->setActivities(null);
            }
        }

        return $this;
    }
}
