<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplyRepository")
 */
class Apply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="applies")
     * @ORM\JoinColumn(nullable=true)
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="applies")
     * @ORM\JoinColumn(nullable=true)
     */
    private $application;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appliedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $answeredAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CVFile")
     */
    private $cvFile;

    private $sendCV;

    const EXPIRED = 'apply.status.expired';
    const PENDING = 'apply.status.pending';
    const ACCEPTED = 'apply.status.accepted';
    const REJECTED = 'apply.status.rejected';

    const NB_ITEMS_LISTING = 15;

    const STATUS = [self::EXPIRED, self::PENDING, self::ACCEPTED, self::REJECTED];

    public function __construct()
    {
        $this->setStatus(self::PENDING);
        $this->appliedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCandidate(): ?User
    {
        return $this->candidate;
    }

    public function setCandidate(?User $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        if (!\in_array($status, self::STATUS, true)) {
            throw new InvalidArgumentException(sprintf('The parameter status required to be one of (%s, %s, %s, %s), %s given.', self::EXPIRED, self::PENDING, self::ACCEPTED, self::REJECTED, $status));
        }

        $this->status = $status;

        return $this;
    }

    public function getAppliedAt(): ?\DateTimeInterface
    {
        return $this->appliedAt;
    }

    public function setAppliedAt(\DateTimeInterface $appliedAt): self
    {
        $this->appliedAt = $appliedAt;

        return $this;
    }

    public function getAnsweredAt(): ?\DateTimeInterface
    {
        return $this->answeredAt;
    }

    public function setAnsweredAt(?\DateTimeInterface $answeredAt): self
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    public function getCvFile(): ?CVFile
    {
        return $this->cvFile;
    }

    public function setCvFile(?CVFile $cvFile): self
    {
        $this->cvFile = $cvFile;

        return $this;
    }

   public function isSendCVEnabled(): ?bool
   {
       return (bool) $this->sendCV;
   }

   public function getSendCV()
   {
       return $this->sendCV;
   }

   public function setSendCV($sendCV): self
   {
       $this->sendCV = $sendCV;

       return $this;
   }
}
