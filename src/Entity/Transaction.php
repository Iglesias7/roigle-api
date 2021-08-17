<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
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
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("Default")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("Default")
     */
    private $time;

    /**
     * @var array<string>
     *
     * @ORM\Column(type="simple_array")
     * 
     * @Groups("Default")
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     * 
     * 
     */
    private $Compte;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getAction(): array
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action[] = $action;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->Compte;
    }

    public function setCompte(?Compte $Compte): self
    {
        $this->Compte = $Compte;

        return $this;
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
