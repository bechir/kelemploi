<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
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
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     */
    private $coverImage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActivated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isArchived;

    /**
     * @Gedmo\Slug(fields={"title", "id"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $viewCount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    const NB_ITEMS_ADMIN_LISTING = 25;
    const NB_ITEMS_LISTING = 10;

    public function __construct()
    {
        $this->viewCount = 0;
        $this->isArchived = false;
        $this->isActivated = false;
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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updated()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(?bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(?bool $isArchived): self
    {
        $this->isArchived = $isArchived;

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

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(?int $viewCount): self
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
}
