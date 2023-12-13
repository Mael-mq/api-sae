<?php

namespace App\Entity;

use App\Repository\ExerciceAppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceAppRepository::class)]
class ExerciceApp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'ExerciceApp', targetEntity: ExerciceAppUser::class)]
    private Collection $exerciceAppUsers;

    public function __construct()
    {
        $this->exerciceAppUsers = new ArrayCollection();
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

    /**
     * @return Collection<int, ExerciceAppUser>
     */
    public function getExerciceAppUsers(): Collection
    {
        return $this->exerciceAppUsers;
    }

    public function addExerciceAppUser(ExerciceAppUser $exerciceAppUser): static
    {
        if (!$this->exerciceAppUsers->contains($exerciceAppUser)) {
            $this->exerciceAppUsers->add($exerciceAppUser);
            $exerciceAppUser->setExerciceApp($this);
        }

        return $this;
    }

    public function removeExerciceAppUser(ExerciceAppUser $exerciceAppUser): static
    {
        if ($this->exerciceAppUsers->removeElement($exerciceAppUser)) {
            // set the owning side to null (unless already changed)
            if ($exerciceAppUser->getExerciceApp() === $this) {
                $exerciceAppUser->setExerciceApp(null);
            }
        }

        return $this;
    }
}
