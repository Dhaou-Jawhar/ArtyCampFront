<?php
namespace  App\Entity\Traits;
trait Timestampable {

    #[ORM\Column(options:["default"=>"CURRENT_TIMESTAMP"] )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(options:["default"=>"CURRENT_TIMESTAMP"] )]
    private ?\DateTimeImmutable $updatedAt = null;

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

}