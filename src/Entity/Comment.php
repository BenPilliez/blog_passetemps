<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Le champ auteur ne peut être vide")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="Le champ contenu ne peut être vide")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comment")
     * @ORM\JoinColumn(nullable=true)
     */
    private $post;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published = false;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="commentChildrens", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="comment", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commentChildrens;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->commentChildrens = new ArrayCollection();
    }

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
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getComment(): ?self
    {
        return $this->comment;
    }

    public function setComment(?self $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommentChildrens(): Collection
    {
        return $this->commentChildrens;
    }

    public function addCommentChildren(Comment $commentChildren): self
    {
        if (!$this->commentChildrens->contains($commentChildren)) {
            $this->commentChildrens[] = $commentChildren;
            $commentChildren->setComment($this);
        }

        return $this;
    }

    public function removeCommentChildren(Comment $commentChildren): self
    {
        if ($this->commentChildrens->contains($commentChildren)) {
            $this->commentChildrens->removeElement($commentChildren);
            // set the owning side to null (unless already changed)
            if ($commentChildren->getComment() === $this) {
                $commentChildren->setComment(null);
            }
        }

        return $this;
    }
}
