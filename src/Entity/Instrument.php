<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["coursApp:read", "instrument:read", "userInstrument:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["coursApp:read", "instrument:read", "userInstrument:read"])]
    private ?string $Name = null;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: CoursApp::class)]
    private Collection $coursApps;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: UserInstrument::class)]
    private Collection $userInstruments;

    public function __construct()
    {
        $this->coursApps = new ArrayCollection();
        $this->userInstruments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, CoursApp>
     */
    public function getCoursApps(): Collection
    {
        return $this->coursApps;
    }

    public function addCoursApp(CoursApp $coursApp): static
    {
        if (!$this->coursApps->contains($coursApp)) {
            $this->coursApps->add($coursApp);
            $coursApp->setInstrument($this);
        }

        return $this;
    }

    public function removeCoursApp(CoursApp $coursApp): static
    {
        if ($this->coursApps->removeElement($coursApp)) {
            // set the owning side to null (unless already changed)
            if ($coursApp->getInstrument() === $this) {
                $coursApp->setInstrument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserInstrument>
     */
    public function getUserInstruments(): Collection
    {
        return $this->userInstruments;
    }

    public function addUserInstrument(UserInstrument $userInstrument): static
    {
        if (!$this->userInstruments->contains($userInstrument)) {
            $this->userInstruments->add($userInstrument);
            $userInstrument->setInstrument($this);
        }

        return $this;
    }

    public function removeUserInstrument(UserInstrument $userInstrument): static
    {
        if ($this->userInstruments->removeElement($userInstrument)) {
            // set the owning side to null (unless already changed)
            if ($userInstrument->getInstrument() === $this) {
                $userInstrument->setInstrument(null);
            }
        }

        return $this;
    }
}
