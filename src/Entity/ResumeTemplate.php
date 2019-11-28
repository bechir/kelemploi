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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResumeTemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ResumeTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title", "id"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     */
    private $coverImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActivated;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $viewCount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="resumeTemplate")
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasWordFormat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasPdfFormat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasExcelFormat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasPptFormat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResumeTemplateFile", mappedBy="resumeTemplate", orphanRemoval=true)
     */
    private $templateFiles;

    const NB_ITEMS_ADMIN_LISTING = 25;
    const NB_ITEMS_LISTING = 10;
    const NB_ITEMS_HOME = 3;

    public function __construct()
    {
        $this->viewCount = 0;
        $this->isActivated = true;
        $this->comments = new ArrayCollection();
        $this->templateFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCoverImage(): ?Image
    {
        return $this->coverImage;
    }

    public function setCoverImage(?Image $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): self
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setResumeTemplate($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getResumeTemplate() === $this) {
                $comment->setResumeTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updated()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function created()
    {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getHasWordFormat(): ?bool
    {
        return $this->hasWordFormat;
    }

    public function setHasWordFormat(?bool $hasWordFormat): self
    {
        $this->hasWordFormat = $hasWordFormat;

        return $this;
    }

    public function getHasPdfFormat(): ?bool
    {
        return $this->hasPdfFormat;
    }

    public function setHasPdfFormat(?bool $hasPdfFormat): self
    {
        $this->hasPdfFormat = $hasPdfFormat;

        return $this;
    }

    public function getHasExcelFormat(): ?bool
    {
        return $this->hasExcelFormat;
    }

    public function setHasExcelFormat(?bool $hasExcelFormat): self
    {
        $this->hasExcelFormat = $hasExcelFormat;

        return $this;
    }

    public function getHasPptFormat(): ?bool
    {
        return $this->hasPptFormat;
    }

    public function setHasPptFormat(?bool $hasPptFormat): self
    {
        $this->hasPptFormat = $hasPptFormat;

        return $this;
    }

    public function getResumeTemplateFile(): ?ResumeTemplateFile
    {
        return $this->resumeTemplateFile;
    }

    /**
     * @return Collection|ResumeTemplateFile[]
     */
    public function getTemplateFiles(): Collection
    {
        return $this->templateFiles;
    }

    public function addTemplateFile(ResumeTemplateFile $templateFile): self
    {
        if (!$this->templateFiles->contains($templateFile)) {
            $this->templateFiles[] = $templateFile;
            $templateFile->setResumeTemplate($this);
        }

        return $this;
    }

    public function removeTemplateFile(ResumeTemplateFile $templateFile): self
    {
        if ($this->templateFiles->contains($templateFile)) {
            $this->templateFiles->removeElement($templateFile);
            // set the owning side to null (unless already changed)
            if ($templateFile->getResumeTemplate() === $this) {
                $templateFile->setResumeTemplate(null);
            }
        }

        return $this;
    }
}
