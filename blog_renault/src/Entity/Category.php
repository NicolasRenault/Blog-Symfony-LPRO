<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Query\AST\Functions\LengthFunction;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var PersistentCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="categories")
     */
    private $articles;

    private $nbArticles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->nbArticles = 0;
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

    /**
     * Undocumented function
     *
     * @return PersistentCollection|null
     */
    public function getArticles(): ? PersistentCollection
    {
        return $this->articles;
    }

    /**
     * Undocumented function
     *
     * @param Article $article
     * @return self
     */
    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Article $article
     * @return self
     */
    public function removeArticle(Article $article): self
    {
        $this->articles->removeElement($article);
        return $this;
    }

    /**
     * Get the value of nbArticles
     */ 
    public function getNbArticles()
    {
        return $this->nbArticles;
    }

    /**
     * Set the value of nbArticles
     *
     * @return  self
     */ 
    public function setNbArticles($nbArticles): self
    {
        $this->nbArticles = $nbArticles;

        return $this;
    }

    /**
     * Set the value of NbArticles from the lenght of $articles
     *
     * @return void
     */
    public function countNbArticles()
    {
        $this->setNbArticles(count($this->getArticles()->getValues()));
    }
}
