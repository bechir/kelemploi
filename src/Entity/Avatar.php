<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 * @Vich\Uploadable
 */
class Avatar implements \Serializable
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
     * @Vich\UploadableField(mapping="user", fileNameProperty="src")
     */
    private $avatarFile;

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

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?File $avatarFile = null): self
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
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
     */
    public function validate(ExecutionContextInterface $context)
    {
        // do your own validation
        if (!\in_array($this->avatarFile->getMimeType(), [
            'image/jpeg',
            'image/jpg',
            'image/png',
        ], true)) {
            $context
                ->buildViolation('Erreur de format (Insérer uniquement une image au format jpg ou png)')
                ->atPath('avatarFile')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function checkSize(ExecutionContextInterface $context)
    {
        // do your own validation
        if ($this->avatarFile->getSize() > '500000') {
            $context
                ->buildViolation('Veuillez uploader un fichier inférieur à 5M.')
                ->atPath('avatarFile')
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
