<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobBookmarkRepository")
 */
class JobBookmark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Application")
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookmarkedJobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCandidate(): ?User
    {
        return $this->candidate;
    }

    public function setCandidate(?User $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }
}
