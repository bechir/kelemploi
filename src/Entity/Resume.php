<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResumeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Resume
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JobCategory")
     */
    private $jobCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StudyLevel")
     */
    private $studyLevel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Experience")
     */
    private $experienceLevel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $about;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", cascade={"persist"})
     */
    private $skills;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Education", mappedBy="resume", cascade={"persist", "remove"})
     */
    private $educations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkExperience", mappedBy="resume", cascade={"persist", "remove"})
     */
    private $workExperiences;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CVFile", inversedBy="resume", cascade={"persist", "remove"})
     */
    private $cv;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SocialProfile", mappedBy="resume", cascade={"persist", "remove"})
     */
    private $socialProfiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProfessionalSkill", mappedBy="resume", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $proSkills;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Portfolio", mappedBy="resume", cascade={"persist", "remove"})
     */
    private $portfolios;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="resume", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    const NB_IMTEMS_HOME = 7;
    const NB_IMTEMS_SIMILAR = 7;
    const NB_ITEMS_LISTING = 13;
    const NB_ITEMS_ADMIN_LISTING = 50;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->workExperiences = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
        $this->socialProfiles = new ArrayCollection();
        $this->proSkills = new ArrayCollection();
        $this->portfolios = new ArrayCollection();
        $this->slug = \uniqid('', true);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getJobCategory(): ?JobCategory
    {
        return $this->jobCategory;
    }

    public function setJobCategory(?JobCategory $jobCategory): self
    {
        $this->jobCategory = $jobCategory;

        return $this;
    }

    public function getStudyLevel(): ?StudyLevel
    {
        return $this->studyLevel;
    }

    public function setStudyLevel(?StudyLevel $studyLevel): self
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }

    public function getExperienceLevel(): ?Experience
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(?Experience $experienceLevel): self
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->contains($skill)) {
            $this->skills->removeElement($skill);
        }

        return $this;
    }

    /**
     * @return Collection|Education[]
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(Education $education): self
    {
        if (!$this->educations->contains($education)) {
            $this->educations[] = $education;
            $education->setResume($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): self
    {
        if ($this->educations->contains($education)) {
            $this->educations->removeElement($education);
            // set the owning side to null (unless already changed)
            if ($education->getResume() === $this) {
                $education->setResume(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WorkExperience[]
     */
    public function getWorkExperiences(): Collection
    {
        return $this->workExperiences;
    }

    public function addWorkExperience(WorkExperience $workExperience): self
    {
        if (!$this->workExperiences->contains($workExperience)) {
            $this->workExperiences[] = $workExperience;
            $workExperience->setResume($this);
        }

        return $this;
    }

    public function removeWorkExperience(WorkExperience $workExperience): self
    {
        if ($this->workExperiences->contains($workExperience)) {
            $this->workExperiences->removeElement($workExperience);
            // set the owning side to null (unless already changed)
            if ($workExperience->getResume() === $this) {
                $workExperience->setResume(null);
            }
        }

        return $this;
    }

    public function getCv(): ?CVFile
    {
        return $this->cv;
    }

    public function setCv(?CVFile $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * @return Collection|SocialProfile[]
     */
    public function getSocialProfiles(): Collection
    {
        return $this->socialProfiles;
    }

    public function addSocialProfile(SocialProfile $socialProfile): self
    {
        if (!$this->socialProfiles->contains($socialProfile)) {
            $this->socialProfiles[] = $socialProfile;
            $socialProfile->setResume($this);
        }

        return $this;
    }

    public function removeSocialeProfil(SocialProfile $socialProfile): self
    {
        if ($this->socialProfiles->contains($socialProfile)) {
            $this->socialProfiles->removeElement($socialProfile);
            // set the owning side to null (unless already changed)
            if ($socialProfile->getResume() === $this) {
                $socialProfile->setResume(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return '';
    }

    /**
     * @return Collection|ProfessionalSkill[]
     */
    public function getProSkills(): Collection
    {
        return $this->proSkills;
    }

    public function addProSkill(ProfessionalSkill $proSkill): self
    {
        if (!$this->proSkills->contains($proSkill)) {
            $this->proSkills[] = $proSkill;
            $proSkill->setResume($this);
        }

        return $this;
    }

    public function removeProSkill(ProfessionalSkill $proSkill): self
    {
        if ($this->proSkills->contains($proSkill)) {
            $this->proSkills->removeElement($proSkill);
            // set the owning side to null (unless already changed)
            if ($proSkill->getResume() === $this) {
                $proSkill->setResume(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Portfolio[]
     */
    public function getPortfolios(): Collection
    {
        return $this->portfolios;
    }

    public function addPortfolio(Portfolio $portfolio): self
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios[] = $portfolio;
            $portfolio->setResume($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        if ($this->portfolios->contains($portfolio)) {
            $this->portfolios->removeElement($portfolio);
            // set the owning side to null (unless already changed)
            if ($portfolio->getResume() === $this) {
                $portfolio->setResume(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newResume = null === $user ? null : $this;
        if ($newResume !== $user->getResume()) {
            $user->setResume($newResume);
        }

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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function onCreateOnUpdate(): self
    {
        return $this->setUpdatedAt(new \DateTime());
    }
}
