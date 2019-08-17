<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Application
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", cascade={"persist"})
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $interlocutor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JobCategory")
     */
    private $postCategory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jobTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $jobDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbCandidatesToRecruit;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DateInterval", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dates;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContractType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StudyLevel")
     */
    private $minStudyLevel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     */
    private $requiredLanguages;

    /**
     * @Gedmo\Slug(fields={"jobTitle", "workTime", "id"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Experience")
     */
    private $experience;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $benefits;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $responsibilities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JobGender")
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tools;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $viewCount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Apply", mappedBy="application")
     */
    private $applies;

    const FENCED = 'application.status.fenced';
    const ONGOING = 'application.status.ongoing';
    const STATUS = [self::FENCED, self::ONGOING];

    const NUM_ITEMS_HOME = 5;

    public function __construct()
    {
        $this->setStatus(self::ONGOING);
        $this->viewCount = 0;

        $this->dates = new DateInterval();
        $this->applies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getInterlocutor(): ?User
    {
        return $this->interlocutor;
    }

    public function setInterlocutor(?User $interlocutor): self
    {
        $this->interlocutor = $interlocutor;

        return $this;
    }

    public function getPostCategory(): ?JobCategory
    {
        return $this->postCategory;
    }

    public function setPostCategory(?JobCategory $postCategory): self
    {
        $this->postCategory = $postCategory;

        return $this;
    }

    public function getNbCandidatesToRecruit(): ?int
    {
        return $this->nbCandidatesToRecruit;
    }

    public function nbCandidatesStr(): string
    {
        return $this->nbCandidatesToRecruit ?: '-';
    }

    public function setNbCandidatesToRecruit(?int $nbCandidatesToRecruit): self
    {
        $this->nbCandidatesToRecruit = $nbCandidatesToRecruit;

        return $this;
    }

    public function getDates(): ?DateInterval
    {
        return $this->dates;
    }

    public function setDates(DateInterval $dates): self
    {
        $this->dates = $dates;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    public function setJobDescription(string $jobDescription): self
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    public function getContractType(): ?ContractType
    {
        return $this->contractType;
    }

    public function setContractType(?ContractType $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getWorkTime(): ?string
    {
        return $this->workTime;
    }

    public function setWorkTime(?string $workTime): self
    {
        $this->workTime = $workTime;

        return $this;
    }

    public function getMinStudyLevel(): ?StudyLevel
    {
        return $this->minStudyLevel;
    }

    public function setMinStudyLevel(?StudyLevel $studyLevel): self
    {
        $this->minStudyLevel = $studyLevel;

        return $this;
    }

    public function getRequiredLanguages(): ?Language
    {
        return $this->requiredLanguages;
    }

    public function setRequiredLanguages(?Language $requiredLanguages): self
    {
        $this->requiredLanguages = $requiredLanguages;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        if (!\in_array($status, self::STATUS, true)) {
            throw new InvalidArgumentException(sprintf('The parameter status required to be one of (%s, %s), %s given.', self::ONGOING, self::FENCED, $status));
        }
        $this->status = $status;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getBenefits(): ?string
    {
        return $this->benefits;
    }

    public function setBenefits(?string $benefits): self
    {
        $this->benefits = $benefits;

        return $this;
    }

    public function getResponsibilities(): ?string
    {
        return $this->responsibilities;
    }

    public function setResponsibilities(?string $responsibilities): self
    {
        $this->responsibilities = $responsibilities;

        return $this;
    }

    public function getGender(): ?JobGender
    {
        return $this->gender;
    }

    public function setGender(?JobGender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getTools(): ?string
    {
        return $this->tools;
    }

    public function setTools(?string $tools): self
    {
        $this->tools = $tools;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function increaseViewCount(): self
    {
        ++$this->viewCount;

        return $this;
    }

    public function isOwner(?User $user): bool
    {
        return (bool) $this->company->getOwners()->contains($user);
    }

    /**
     * @return Collection|Apply[]
     */
    public function getApplies(): Collection
    {
        return $this->applies;
    }

    public function addApply(Apply $apply): self
    {
        if (!$this->applies->contains($apply)) {
            $this->applies[] = $apply;
            $apply->setApplication($this);
        }

        return $this;
    }

    public function removeApply(Apply $apply): self
    {
        if ($this->applies->contains($apply)) {
            $this->applies->removeElement($apply);
            // set the owning side to null (unless already changed)
            if ($apply->getApplication() === $this) {
                $apply->setApplication(null);
            }
        }

        return $this;
    }
}
