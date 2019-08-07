<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResumeRepository")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Portfolio", mappedBy="resume", cascade={"persist", "remove"})
     */
    private $portfolio;

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

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->workExperiences = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
        $this->socialProfiles = new ArrayCollection();
        $this->proSkills = new ArrayCollection();
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

    /**
     * @return Collection|Portfolio[]
     */
    public function getPortfolio(): Collection
    {
        return $this->portfolio;
    }

    public function addPortfolio(Portfolio $portfolio): self
    {
        if (!$this->portfolio->contains($portfolio)) {
            $this->portfolio[] = $portfolio;
            $portfolio->setResume($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        if ($this->portfolio->contains($portfolio)) {
            $this->portfolio->removeElement($portfolio);
            // set the owning side to null (unless already changed)
            if ($portfolio->getResume() === $this) {
                $portfolio->setResume(null);
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
}
