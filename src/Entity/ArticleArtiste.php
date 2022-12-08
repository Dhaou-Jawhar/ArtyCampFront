<?php

namespace App\Entity;

//use App\Entity\Traits\Timestampable;
use App\Repository\ArticleArtisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\HasLifecycleCallbacks] //écouter les différent évènement de cycle de vie de cette entitée
#[ORM\Table(name: "ArticleArtiste")]
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ArticleArtisteRepository::class)]
class ArticleArtiste
{
 // use Timestampable;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_article")]
    private ?int $idArticle = null;

    #[Assert\NotBlank(message: 'Ce champ est obligatoire')]
    #[Assert\Length(min: 3,minMessage: 'Ce champ doit contenir 3 caractères au minimum')]
    #[ORM\Column(length: 255)]
    private ?string $nomA = null;

    #[Assert\NotBlank(message: 'Ce champ est obligatoire')]
    #[Assert\Length(min: 10,minMessage:'Ce champ doit contenir 10 caractères au minimum' )]
    #[ORM\Column(type: "text",length: 255)]
    private ?string $descriptionA = null;







    #[ORM\Column(options:["default"=>"CURRENT_TIMESTAMP"] )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(options:["default"=>"CURRENT_TIMESTAMP"] )]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotBlank(message: 'Veuillez insérer une image ')]
    #[Assert\Image(maxSize: "1M")]
    #[Vich\UploadableField(mapping: 'article_image', fileNameProperty: 'imageH')]
    private ?File $imageFile = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageH = null;

    #[ORM\ManyToOne(inversedBy: 'articleArtistes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;






    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function setIdArticle(int $idArticle): self
    {
        $this->idArticle = $idArticle;

        return $this;
    }

    public function getDescriptionA(): ?string
    {
        return $this->descriptionA;
    }

    public function setDescriptionA(?string $descriptionA): self
    {
        $this->descriptionA = $descriptionA;

        return $this;
    }


    public function getNomA(): ?string
    {
        return $this->nomA;
    }

    public function setNomA(?string $nomA): self
    {
        $this->nomA = $nomA;

        return $this;
    }



    public function getImageH(): ?string
    {
        return $this->imageH;
    }

    public function setImageH(?string $imageH): self
    {
        $this->imageH = $imageH;

        return $this;
    }
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdatedAt(new \DateTimeImmutable);
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    //cette méthode va nous permettre de mettre à jour nos timestamps
    //avant la création et la modification d'un article on va appeler cette méthode

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(){
        if ($this->getCreatedAt()===null){
            $this->setCreatedAt(new \DateTimeImmutable()); //DateTimeImmutable():ajouter la date et l'heure actuelle:on ne peut pas le modifier

        }

        $this->setUpdatedAt(new \DateTimeImmutable());

    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }









}
