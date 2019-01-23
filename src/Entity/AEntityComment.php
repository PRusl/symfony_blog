<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HasLifecycleCallbacks
 * @ORM\MappedSuperclass()
 */
abstract class AEntityComment
{
    const FORMAT_DATE = 'Y-m-d';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @Assert\Regex(
     *     pattern="/^[A-Z]([A-Za-z]*) +[A-Z]([A-Za-z]*)$/",
     *     match=true,
     *     message="Your name must contain two words beginning with the uppercase"
     * )
     * @ORM\Column(type="string", length=255)
     */
    protected $author;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $created_at;

    protected $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOwner(): ?IComment
    {
        return $this->owner;
    }

    public function setOwner($owner): ?self
    {
        $this->owner = $owner;

        return $this;
    }

    public function __toString()
    {
        return sprintf(
            '%s at %s: %s',
            $this->getAuthor(),
            date_format($this->getCreatedAt(), self::FORMAT_DATE),
            $this->getContent());
    }

    public function toArray()
    {
        return [
            'id'         => $this->getId(),
            'author'     => $this->getAuthor(),
            'content'    => $this->getContent(),
            'created_at' => date_format($this->getCreatedAt(), self::FORMAT_DATE),
        ];
    }

    /**
     * @PrePersist
     */
    public
    function prepareBeforeCreate() {
        $this->created_at = new DateTime();
    }
}
