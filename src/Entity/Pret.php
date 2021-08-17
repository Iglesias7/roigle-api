<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PretRepository")
 */
class Pret
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Groups("Default")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * 
     * @Groups("Default")
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("Default")
     */
    private $delai;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("Default")
     */
    private $message;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="prets")
     */
    private $userPrets;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="pretsSend")
     */
    private $userPretsSend;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", mappedBy="empruntsSend")
     */
    private $userEmpruntsSend;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", mappedBy="emprunts")
     */
    private $userEmprunts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups("Default")
     */
    private $demandeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups("Default")
     */
    private $donneur;

    public function __construct()
    {
        $this->userPrets = new ArrayCollection();
        $this->userPretsSend = new ArrayCollection();
        $this->userEmprunts = new ArrayCollection();
        $this->userEmpruntsSend = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(?\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserPrets(): Collection
    {
        return $this->userPrets;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserPretsSend(): Collection
    {
        return $this->userPretsSend;
    }

     /**
     * @return Collection|User[]
     */
    public function getUserEmpruntsSend(): Collection
    {
        return $this->userEmpruntsSend;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserEmprunts(): Collection
    {
        return $this->userEmprunts;
    }

    public function getDemandeur(): ?User
    {
        return $this->demandeur;
    }

    public function setDemandeur(?User $demandeur): self
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getDonneur(): ?User
    {
        return $this->donneur;
    }

    public function setDonneur(?User $donneur): self
    {
        $this->donneur = $donneur;

        return $this;
    }

}
