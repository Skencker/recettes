<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(targetEntity=Recette::class, mappedBy="Category")
     */
    private $recettes;

    /**
     * @ORM\OneToMany(targetEntity=Recette::class, mappedBy="category")
     */
    private $recette;

    // /**
    //  * @ORM\OneToMany(targetEntity=Recette::class, mappedBy="relation")
    //  */
    // private $recettes;



    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->recette = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
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
     * @return Collection|Recette[]
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecettes(Recette $recettes): self
    {
        if (!$this->recettes->contains($recettes)) {
            $this->recettes[] = $recettes;
            $recettes->setCategory($this);
        }

        return $this;
    }

    public function removeRecettes(Recette $recettes): self
    {
        if ($this->recettes->removeElement($recettes)) {
            // set the owning side to null (unless already changed)
            if ($recettes->getCategory() === $this) {
                $recettes->setCategory(null);
            }
        }

        return $this;
    }


}
