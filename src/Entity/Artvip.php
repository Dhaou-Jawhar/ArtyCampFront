<?php

namespace App\Entity;

use App\Repository\ArtvipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtvipRepository::class)]
class Artvip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $artID = null;

    #[Assert\NotBlank(message: "il faut un Nom")]
    #[ORM\Column(length: 255)]
    private ?string $artnom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "il faut une description")]
    #[Assert\Length(
        min: 5,
        minMessage: 'Art Description minimum 5 characters',
    )]
    private ?string $artdescription = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "il faut une reference")]
    #[Assert\Length(
        min: 2,
        max:5,
        minMessage: 'Art Description minimum 2 characters',
        maxMessage: 'Max Reference est 5 Characteres',
    )]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;


    public function getArtID(): ?int
    {
        return $this->artID;
    }

    public function getArtNom(): ?string
    {
        return $this->artnom;
    }

    public function setArtNom(string $artnom): self
    {
        $this->artnom = $artnom;

        return $this;
    }

    public function getArtDescription(): ?string
    {
        return $this->artdescription;
    }

    public function setArtDescription(string $artdescription): self
    {
        $this->artdescription = $artdescription;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

   public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Avis3d>
     */
    public function getAvisid(): Collection
    {
        return $this->avisid;
    }

    public function addAvisid(Avis3d $avisid): self
    {
        if (!$this->avisid->contains($avisid)) {
            $this->avisid->add($avisid);
            $avisid->setArtvip($this);
        }

        return $this;
    }

    public function removeAvisid(Avis3d $avisid): self
    {
        if ($this->avisid->removeElement($avisid)) {
            // set the owning side to null (unless already changed)
            if ($avisid->getArtvip() === $this) {
                $avisid->setArtvip(null);
            }
        }

        return $this;
    }
}
