<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
        * @Assert\Expression(
        *  "this.getContent() != this.getTitle()",
        *  message="Le contenu ne doit pas être le même que le titre"
        * )
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Expression(
     *  "this.getNbViews() >= 0",
     *  message="Le nombre de vue doit être supérieur ou égal à 0"
     * )
     */
    private $nb_views;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @var PersistentCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article")
     */
    private $comments;

    /**
     * @var PersistentCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
     * @ORM\JoinTable(name="asso_article_categorie")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
     */
    private $user;

    /**
    * @var string
    * @ORM\Column(unique=true)
    * @Gedmo\Slug(fields={"title", "author"})
    */
    private $slug;
    

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * Undocumented function
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Undocumented function
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Undocumented function
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * Undocumented function
     *
     * @param \DateTimeInterface $created_at
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * Undocumented function
     *
     * @param \DateTimeInterface $updated_at
     * @return self
     */
    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Undocumented function
     *
     * @param string $author
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return integer|null
     */
    public function getNbViews(): ?int
    {
        return $this->nb_views;
    }

    /**
     * Undocumented function
     *
     * @param integer $nb_views
     * @return self
     */
    public function setNbViews(int $nb_views): self
    {
        $this->nb_views = $nb_views;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return boolean|null
     */
    public function getPublished(): ?bool
    {
        return $this->published;
    }

    /**
     * Undocumented function
     *
     * @param boolean $published
     * @return self
     */
    public function setPublished(bool $published): self
    {
        $this->published = $published;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return PersistentCollection|null
     */
    public function getComments(): ? PersistentCollection                                                                                                                                                                                                                                                                                                                                                                                     
    {
        return $this->comments;
    }

    /**
     * Undocumented function
     *
     * @param Comment $comment
     * @return self
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Comment $comment
     * @return self
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            $comment->setArticle(null);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return PersistentCollection|null
     */
    public function getCategories(): ? PersistentCollection
    {
        return $this->categories;
    }

    /**
     * Undocumented function
     *
     * @param Category $category
     * @return self
     */
    public function addCategory(Category $category): self
    {
        if(!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addArticle($this);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Category $category
     * @return self
     */
    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeArticle($this);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return User|null
     */
    public function getUser(): ? Article
    {
        return $this->user;
    }

    /**
     * Undocumented function
     *
     * @param User|null $user
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    
    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Undocumented function
     *
     * @param string $author
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    /**
     * Force the author field to "Anonymous" if it's empty
     * @ORM\PrePersist()
     */
    public function forceAuthor()
    {
        if(is_null($this->author)) {
            $this->setAuthor("Anonymous");
        }
    }
}
