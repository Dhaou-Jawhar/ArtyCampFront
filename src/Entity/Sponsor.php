<?php

namespace App\Entity;

use App\Repository\SponsorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponsorRepository::class)]
class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idSponsor = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    #[Assert\Positive(message:"Numéro doit etre positif")]
    private ?int $phoneSociete = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    #[Assert\Positive(message:"Montant doit etre positif")]
    private ?int $montant = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    private ?string $nomSociete = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplissez ce champ!!")]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    private ?string $emailSociete = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'sponsor')]
    private Collection $event;

    public function __construct()
    {
        $this->event = new ArrayCollection();
    }


    public function getIdSponsor(): ?int
    {
        return $this->idSponsor;
    }

    public function getPhoneSociete(): ?int
    {
        return $this->phoneSociete;
    }

    public function setPhoneSociete(int $phoneSociete): self
    {
        $this->phoneSociete = $phoneSociete;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNomSociete(): ?string
    {
        return $this->nomSociete;
    }

    public function setNomSociete(string $nomSociete): self
    {
        $this->nomSociete = $nomSociete;

        return $this;
    }

    public function getEmailSociete(): ?string
    {
        return $this->emailSociete;
    }

    public function setEmailSociete(string $emailSociete): self
    {
        $this->emailSociete = $emailSociete;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event->add($event);
            $event->addSponsor($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->event->removeElement($event)) {
            $event->removeSponsor($this);
        }

        return $this;
    }


}
