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
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur existe déjà")
 * 
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser implements EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civility")
     * @ORM\JoinColumn(nullable=true)
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submittedAt;

    /**
     * @var Avatar
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Avatar", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     * @Assert\Country
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, unique=false)
     */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="owners")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     */
    private $region;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $viewCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gender")
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccountType")
     */
    private $accountType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Nationality")
     */
    private $nationality;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Resume", inversedBy="user", cascade={"persist", "remove"})
     */
    private $resume;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Apply", mappedBy="candidate")
     */
    private $applies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyBookmark", mappedBy="candidate")
     */
    private $bookmarkedCompanies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JobBookmark", mappedBy="candidate")
     */
    private $bookmarkedJobs;

    const NUM_ITEMS = 15;

    const EMPLOYER = 'app.employer';
    const CANDIDATE = 'app.candidate';

    public function __construct()
    {
        parent::__construct();
        $this->locale = 'fr';

        $this->viewCount = 0;
        $this->applies = new ArrayCollection();
        $this->bookmarkedCompanies = new ArrayCollection();
        $this->bookmarkedJobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        if(!$this->username) {
            $this->username = substr($email, 0, strpos($email, '@'));
        }

        return $this;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setCivility(?Civility $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName
            ? $this->firstName . ' ' . $this->lastName
            : $this->username;
    }

    public function getJobTitle(): string
    {
        return ($this->haveResume() && $this->resume->getTitle())
            ? $this->resume->getTitle()
            : $this->getFullName();
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeInterface
    {
        return $this->submittedAt;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
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

    public function haveCompany(): bool
    {
        return null !== $this->company;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->phoneNumber,
            $this->email,
            $this->region,
            $this->plainPassword,
            $this->password,
            $this->submittedAt,
            $this->roles,
            $this->avatar,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->phoneNumber,
            $this->email,
            $this->region,
            $this->plainPassword,
            $this->password,
            $this->submittedAt,
            $this->roles,
            $this->avatar,
        ] = unserialize($serialized);
    }

    /**
     * @ORM\PrePersist
     */
    public function setSubmittedAt()
    {
        $this->submittedAt = new \DateTime();
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($this->password !== $user->getPassword()) {
            return false;
        }
        if ($this->salt !== $user->getSalt()) {
            return false;
        }
        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAccountType(): ?AccountType
    {
        return $this->accountType;
    }

    public function setAccountType(?AccountType $accountType): self
    {
        $this->accountType = $accountType;

        return $this;
    }

    public function haveResume(): bool
    {
        return null !== $this->resume;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function isCandidate(): bool
    {
        return $this->accountType && $this->accountType->getName() == self::CANDIDATE;
    }

    public function isEmployer(): bool
    {
        return $this->accountType && $this->accountType->getName() == self::EMPLOYER;
    }

    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    public function setResume(?Resume $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateRoles()
    {
        if($this->isEmployer()) {
            $this->addRole('ROLE_EMPLOYER');
        }
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
            $apply->setCandidate($this);
        }

        return $this;
    }

    public function removeApply(Apply $apply): self
    {
        if ($this->applies->contains($apply)) {
            $this->applies->removeElement($apply);
            // set the owning side to null (unless already changed)
            if ($apply->getCandidate() === $this) {
                $apply->setCandidate(null);
            }
        }

        return $this;
    }

    public function haveApplied(Application $app): bool
    {
        foreach ($this->applies as $apply) {
            if($apply->getApplication() == $app)
                return true;
        }
        return false;
    }

    /**
     * @return Collection|CompanyBookmark[]
     */
    public function getBookmarkedCompanies(): Collection
    {
        return $this->bookmarkedCompanies;
    }

    public function addBookmarkedCompany(CompanyBookmark $bookmarkedCompany): self
    {
        if (!$this->bookmarkedCompanies->contains($bookmarkedCompany)) {
            $this->bookmarkedCompanies[] = $bookmarkedCompany;
            $bookmarkedCompany->setCandidate($this);
        }

        return $this;
    }

    public function removeBookmarkedCompany(CompanyBookmark $bookmarkedCompany): self
    {
        if ($this->bookmarkedCompanies->contains($bookmarkedCompany)) {
            $this->bookmarkedCompanies->removeElement($bookmarkedCompany);
            // set the owning side to null (unless already changed)
            if ($bookmarkedCompany->getCandidate() === $this) {
                $bookmarkedCompany->setCandidate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobBookmark[]
     */
    public function getBookmarkedJobs(): Collection
    {
        return $this->bookmarkedJobs;
    }

    public function addBookmarkedJob(JobBookmark $bookmarkedJob): self
    {
        if (!$this->bookmarkedJobs->contains($bookmarkedJob)) {
            $this->bookmarkedJobs[] = $bookmarkedJob;
            $bookmarkedJob->setCandidate($this);
        }

        return $this;
    }

    public function removeBookmarkedJob(JobBookmark $bookmarkedJob): self
    {
        if ($this->bookmarkedJobs->contains($bookmarkedJob)) {
            $this->bookmarkedJobs->removeElement($bookmarkedJob);
            // set the owning side to null (unless already changed)
            if ($bookmarkedJob->getCandidate() === $this) {
                $bookmarkedJob->setCandidate(null);
            }
        }

        return $this;
    }
}
