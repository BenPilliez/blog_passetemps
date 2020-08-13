<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Vich\Uploadable()
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotNull(message="Maman, Il faut que tu lui donne un nom")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="categories")
     */
    private $posts;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Maman, il faut lui attribuer une couleur")
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jumbotron;

    /**
     * @Vich\UploadableField(mapping="jumbotrons", fileNameProperty="jumbotron")
     * @Assert\NotNull(message="Maman, il faut que tu lui ajoute une image de fond")
     */
    private $jumbotronFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getJumbotron()
    {
        return $this->jumbotron;
    }

    /**
     * @param mixed $jumbotron
     */
    public function setJumbotron($jumbotron): void
    {
        $this->jumbotron = $jumbotron;
    }

    /**
     * @return mixed
     */
    public function getJumbotronFile()
    {
        return $this->jumbotronFile;
    }

    /**
     * @param mixed $jumbotronFile
     */
    public function setJumbotronFile($jumbotronFile): void
    {
        $this->jumbotronFile = $jumbotronFile;

        if ($jumbotronFile) {
            $this->updatedAt = new \DateTime();
        }
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategories($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getCategories() === $this) {
                $post->setCategories(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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
}
