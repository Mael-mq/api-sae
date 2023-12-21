<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cours:read', 'seance:read', 'messages:read'])]
    private ?int $id = null;

    #[Groups(['cours:read'])]
    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[Assert\NotBlank(message: "L'élève est obligatoire ou n'existe pas")]
    private ?Student $Student = null;

    #[Groups(['cours:read'])]
    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[Assert\NotBlank(message: "Le prof est obligatoire ou n'existe pas")]
    private ?Teacher $Teacher = null;

    #[ORM\OneToMany(mappedBy: 'Cours', targetEntity: Seance::class)]
    private Collection $seances;

    #[ORM\OneToMany(mappedBy: 'Cours', targetEntity: Messages::class)]
    private Collection $messages;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->Student;
    }

    public function setStudent(?Student $Student): static
    {
        $this->Student = $Student;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->Teacher;
    }

    public function setTeacher(?Teacher $Teacher): static
    {
        $this->Teacher = $Teacher;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setCours($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getCours() === $this) {
                $seance->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setCours($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCours() === $this) {
                $message->setCours(null);
            }
        }

        return $this;
    }
}
