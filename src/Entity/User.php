<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['userInstrument:read', 'user:read', 'exerciceAppUser:read', 'coursAppUser:read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['userInstrument:read', 'user:read', 'exerciceAppUser:read', 'coursAppUser:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: UserInstrument::class)]
    private Collection $userInstruments;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: CoursAppUser::class)]
    private Collection $coursAppUsers;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: ExerciceAppUser::class)]
    private Collection $exerciceAppUsers;

    public function __construct()
    {
        $this->userInstruments = new ArrayCollection();
        $this->coursAppUsers = new ArrayCollection();
        $this->exerciceAppUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Méthode getUsername qui permet de retourner le champ qui est utilisé pour l'authentification.
     *
     * @return string
     */
    public function getUsername(): string {
        return $this->getUserIdentifier();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $userInstrument->setUser($this);
        }

        return $this;
    }

    public function removeUserInstrument(UserInstrument $userInstrument): static
    {
        if ($this->userInstruments->removeElement($userInstrument)) {
            // set the owning side to null (unless already changed)
            if ($userInstrument->getUser() === $this) {
                $userInstrument->setUser(null);
            }
        }

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
            $coursAppUser->setUser($this);
        }

        return $this;
    }

    public function removeCoursAppUser(CoursAppUser $coursAppUser): static
    {
        if ($this->coursAppUsers->removeElement($coursAppUser)) {
            // set the owning side to null (unless already changed)
            if ($coursAppUser->getUser() === $this) {
                $coursAppUser->setUser(null);
            }
        }

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
            $exerciceAppUser->setUser($this);
        }

        return $this;
    }

    public function removeExerciceAppUser(ExerciceAppUser $exerciceAppUser): static
    {
        if ($this->exerciceAppUsers->removeElement($exerciceAppUser)) {
            // set the owning side to null (unless already changed)
            if ($exerciceAppUser->getUser() === $this) {
                $exerciceAppUser->setUser(null);
            }
        }

        return $this;
    }


}
