<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: EventRepository::class)]

class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    private ?string $nomev = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    #[Assert\Date(message:"Entrez une date valide!")]
    private ?string $datedeb = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    #[Assert\Date(message:"Entrez une date valide!")]
    protected ?string $datefin = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imagename')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagename = null;

    #[ORM\ManyToMany(targetEntity: Sponsor::class, inversedBy: 'event')]
    #[ORM\JoinTable(name:'eventsponsor')]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "id_sponsor", referencedColumnName: "id_sponsor")]
    private Collection $sponsor;

    public function __construct()
    {
        $this->sponsor = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomev(): ?string
    {
        return $this->nomev;
    }

    public function setNomev(string $nomev): self
    {
        $this->nomev = $nomev;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatedeb(): ?string
    {
        return $this->datedeb;
    }

    public function setDatedeb(string $datedeb): self
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    public function getDatefin(): ?string
    {
        return $this->datefin;
    }

    public function setDatefin(string $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * @return Collection<int, Sponsor>
     */
    public function getSponsor(): Collection
    {
        return $this->sponsor;
    }

    public function addSponsor(Sponsor $sponsor): self
    {
        if (!$this->sponsor->contains($sponsor)) {
            $this->sponsor->add($sponsor);
        }

        return $this;
    }

    public function removeSponsor(Sponsor $sponsor): self
    {
        $this->sponsor->removeElement($sponsor);

        return $this;
    }

    public function getImagename(): ?string
    {
        return $this->imagename;
    }

    public function setImagename(?string $imagename): self
    {
        $this->imagename = $imagename;

        return $this;
    }

    /**
     * @param  File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */

    public function setImageFile( $imageFile = null): void
    {
        $this->imageFile = $imageFile;

    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

}
