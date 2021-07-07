<?php
namespace App\Entity;

use App\Repository\CategorieRepository;
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
     * @var [type]
     */
    private $name;

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
}