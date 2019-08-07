<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PortfolioImageRepository")
 * @Vich\Uploadable
 */
class PortfolioImage implements \Serializable
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
     * @Vich\UploadableField(mapping="portfolio", fileNameProperty="src")
     */
    private $portfolioFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $src;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPortfolioFile(): ?File
    {
        return $this->portfolioFile;
    }

    public function setPortfolioFile(?File $portfolioFile = null): self
    {
        $this->portfolioFile = $portfolioFile;

        if (null !== $portfolioFile) {
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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
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

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        // do your own validation
        if (!\in_array($this->portfolioFile->getMcmeType(), [
            'image/jpeg',
            'image/jpg',
            'image/png',
        ], true)) {
            $context
                ->buildViolation('Erreur de format (Insérer au format JPG, JPEG ou PNG)')
                ->atPath('portfolioFile')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function checkSize(ExecutionContextInterface $context)
    {
        // do your own validation
        if ($this->portfolioFile->getSize() > '500000') {
            $context
                ->buildViolation('Veuillez uploader un fichier inférieur à 5M.')
                ->atPath('portfolioFile')
                ->addViolation();
        }
    }
}
