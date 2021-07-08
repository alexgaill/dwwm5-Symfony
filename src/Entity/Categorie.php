<?php
namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=65, nullable=false)
     * @Assert\Length(
     *      min=8,
     *      max=65,
     *      minMessage="Le titre de la catégorie doit avoir au minimum {{ limit }} caractères",
     *      maxMessage="Le titre de la catégorie doit avoir au maximum {{ limit }} caractères",
     * )
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="categorie")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * return id of Categorie
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * return name of Categorie
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name of Categorie
     *
     * @param string $name
     * @return bool
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return true;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategorie() === $this) {
                $article->setCategorie(null);
            }
        }

        return $this;
    }
}