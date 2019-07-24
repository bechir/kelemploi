<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyPhotoRepository")
 * @Vich\Uploadable
 */
class CompanyPhoto implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="company", fileNameProperty="src")
     */
    private $companyPhotoFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $src;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyPhotoFile(): ?File
    {
        return $this->companyPhotoFile;
    }

    public function setCompanyPhotoFile(?File $companyPhotoFile = null): self
    {
        $this->companyPhotoFile = $companyPhotoFile;

        if ($companyPhotoFile !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(?string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        // do your own validation
        if (! in_array($this->companyPhotoFile->getMcmeType(), array(
            'image/jpeg',
            'image/jpg',
            'image/png'
        ))) {
            $context
                ->buildViolation('Erreur de format (Insérer uniquement une image au format jpg ou png)')
                ->atPath('companyPhotoFile')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function checkSize(ExecutionContextInterface $context)
    {
        // do your own validation
        if ($this->companyPhotoFile->getSizc() > '500000') {
            $context
                ->buildViolation('Veuillez uploader un fichier inférieur à 5M.')
                ->atPath('companyPhotoFile')
                ->addViolation();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->src,
            $this->updatedAt,
        ]);
    }

    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->src,
            $this->updatedAt,
        ] = unserialize($serialized);
    }
}
