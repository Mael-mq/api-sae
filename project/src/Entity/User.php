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
    #[Groups(['user:read', 'cours:read', 'messages:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['userInstrument:read', 'user:read', 'exerciceAppUser:read', 'coursAppUser:read', 'student:read', 'teacher:read', 'cours:read', 'messages:read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['userInstrument:read', 'user:read', 'exerciceAppUser:read', 'coursAppUser:read', 'student:read', 'teacher:read', 'cours:read', 'messages:read'])]
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

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Teacher::class)]
    private Collection $teachers;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Student::class)]
    private Collection $students;

    public function __construct()
    {
        $this->userInstruments = new ArrayCollection();
        $this->coursAppUsers = new ArrayCollection();
        $this->exerciceAppUsers = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->students = new ArrayCollection();
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

    /**
     * @return Collection<int, Teacher>
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): static
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers->add($teacher);
            $teacher->setUser($this);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): static
    {
        if ($this->teachers->removeElement($teacher)) {
            // set the owning side to null (unless already changed)
            if ($teacher->getUser() === $this) {
                $teacher->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setUser($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getUser() === $this) {
                $student->setUser(null);
            }
        }

        return $this;
    }


}
