<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
 */
class Departement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *  @Groups({"region:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     *  @Groups({"region:read"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=40)
     *  @Groups({"region:read"})
     */
    private $nom;



    /**
     * @ORM\OneToMany(targetEntity=Commune::class, mappedBy="departement")
     */
    private $communes;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="departements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    public function __construct()
    {
        $this->communes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }



    /**
     * @return Collection|Commune[]
     */
    public function getCommunes(): Collection
    {
        return $this->communes;
    }

    public function addCommune(Commune $commune): self
    {
        if (!$this->communes->contains($commune)) {
            $this->communes[] = $commune;
            $commune->setDepartement($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): self
    {
        if ($this->communes->contains($commune)) {
            $this->communes->removeElement($commune);
            // set the owning side to null (unless already changed)
            if ($commune->getDepartement() === $this) {
                $commune->setDepartement(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
