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
    #[Groups(["coursApp:read", "instrument:read", "userInstrument:read", "sheet:read", "vaultSheet:read", "cours:read", "teacher:read", "student:read", "customSheet:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["coursApp:read", "instrument:read", "userInstrument:read", "sheet:read", "vaultSheet:read", "cours:read", "teacher:read", "student:read", "customSheet:read"])]
    private ?string $Name = null;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: CoursApp::class)]
    private Collection $coursApps;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: UserInstrument::class)]
    private Collection $userInstruments;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: Sheet::class)]
    private Collection $sheets;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: Cours::class)]
    private Collection $cours;

    #[ORM\OneToMany(mappedBy: 'Instrument', targetEntity: CustomSheet::class)]
    private Collection $customSheets;

    public function __construct()
    {
        $this->coursApps = new ArrayCollection();
        $this->userInstruments = new ArrayCollection();
        $this->sheets = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->customSheets = new ArrayCollection();
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

    /**
     * @return Collection<int, Sheet>
     */
    public function getSheets(): Collection
    {
        return $this->sheets;
    }

    public function addSheet(Sheet $sheet): static
    {
        if (!$this->sheets->contains($sheet)) {
            $this->sheets->add($sheet);
            $sheet->setInstrument($this);
        }

        return $this;
    }

    public function removeSheet(Sheet $sheet): static
    {
        if ($this->sheets->removeElement($sheet)) {
            // set the owning side to null (unless already changed)
            if ($sheet->getInstrument() === $this) {
                $sheet->setInstrument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setInstrument($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getInstrument() === $this) {
                $cour->setInstrument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomSheet>
     */
    public function getCustomSheets(): Collection
    {
        return $this->customSheets;
    }

    public function addCustomSheet(CustomSheet $customSheet): static
    {
        if (!$this->customSheets->contains($customSheet)) {
            $this->customSheets->add($customSheet);
            $customSheet->setInstrument($this);
        }

        return $this;
    }

    public function removeCustomSheet(CustomSheet $customSheet): static
    {
        if ($this->customSheets->removeElement($customSheet)) {
            // set the owning side to null (unless already changed)
            if ($customSheet->getInstrument() === $this) {
                $customSheet->setInstrument(null);
            }
        }

        return $this;
    }
}
