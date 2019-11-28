<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CVFileRepository")
 * @Vich\Uploadable
 */
class CVFile implements \Serializable
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
     * @Vich\UploadableField(mapping="resume", fileNameProperty="src")
     */
    private $cvFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $src;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Resume", mappedBy="cv", cascade={"persist", "remove"})
     */
    private $resume;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCVFile(): ?File
    {
        return $this->cvFile;
    }

    public function setCVFile(?File $cvFile = null): self
    {
        $this->cvFile = $cvFile;

        if (null !== $cvFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    public function setResume(?Resume $resume): self
    {
        $this->resume = $resume;

        // set (or unset) the owning side of the relation if necessary
        $newCv = null === $resume ? null : $this;
        if ($newCv !== $resume->getCv()) {
            $resume->setCv($newCv);
        }

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        // do your own validation
        if (!\in_array($this->cvFile->getMcmeType(), [
            'application/pdf',
            'application/x-pdf',
            'application/msword',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ], true)) {
            $context
                ->buildViolation('Erreur de format (Insérer au format PDF ou Word)')
                ->atPath('cvFile')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function checkSize(ExecutionContextInterface $context)
    {
        // do your own validation
        if ($this->cvFile->getSize() > '500000') {
            $context
                ->buildViolation('Veuillez uploader un fichier inférieur à 5M.')
                ->atPath('cvFile')
                ->addViolation();
        }
    }
}
