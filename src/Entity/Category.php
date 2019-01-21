<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category implements IComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="CategoryComment", mappedBy="owner")
     */
    private $comments;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function setArticle(?Article $article): self
    {
        if ($this->articles->contains($article)) {
            return $this;
        }

        if ($this->articles->add($article)) {
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(?Article $article): self
    {
        if (!$this->articles->contains($article)) {
            return $this;
        }

        if ($this->articles->removeElement($article)) {
            $article->setCategory(null);
        }

        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComment(?AEntityComment $comment): IComment
    {
        if ($this->comments->contains($comment)) {
            return $this;
        }

        if ($this->comments->add($comment)) {
            $comment->setOwner($this);
        }

        return $this;
    }

    public function removeComment(?AEntityComment $comment): IComment
    {
        if (!$this->comments->contains($comment)) {
            return $this;
        }

        if ($this->comments->removeElement($comment)) {
            $comment->setOwner(null);
        }

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->name;
    }
}
