<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $Headline;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $ArticleText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="Articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;

    public function __toString(): string
    {
        return (string) $this->Name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->Headline;
    }

    public function setHeadline(string $Headline): self
    {
        $this->Headline = $Headline;

        return $this;
    }

    public function getArticleText(): ?string
    {
        return $this->ArticleText;
    }

    public function setArticleText(string $ArticleText): self
    {
        $this->ArticleText = $ArticleText;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->Category;
    }

    public function setCategory(?Categories $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->Name,
            $this->Headline,
            $this->ArticleText,
            $this->Date,
            $this->Category,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->Name,
            $this->Headline,
            $this->ArticleText,
            $this->Date,
            $this->Category,
            ) = unserialize($serialized);
    }
}
