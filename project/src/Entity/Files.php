<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FilesRepository::class)]
#[Vich\Uploadable]
class Files
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['files:read'])]
    private ?int $id = null;

    #[Groups(['files:read'])]
    #[ORM\ManyToOne(inversedBy: 'files')]
    private ?Cours $Cours = null;

    #[Groups(['files:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[Groups(['files:read'])]
    #[ORM\Column(nullable: true)]
    private ?int $fileSize = null;

    #[Vich\UploadableField(mapping: 'file_upload', fileNameProperty: 'filePath', size: 'fileSize')]
    private ?File $uploadedFile = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[Groups(['files:read'])]
    private ?Seance $Seance = null;

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

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

        /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $uploadedFile
     */
    public function setUploadedFile(?File $uploadedFile = null): void
    {
        $this->uploadedFile = $uploadedFile;

        if (null !== $uploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

    public function getUploadedFile(): ?File
    {
        return $this->uploadedFile;
    }

    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
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
}
