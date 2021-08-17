<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 * @ORM\Table(name="`match`")
 */
class Match
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
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="match1", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("Default")
     */
    private $joueur1;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="match2", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("Default")
     */
    private $joueur2;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups("Default")
     */
    private $milieux;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups("Default")
     */
    private $bank;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups("Default")
     */
    private $mise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur1(): ?User
    {
        return $this->joueur1;
    }

    public function setJoueur1(User $joueur1): self
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    public function getJoueur2(): ?User
    {
        return $this->joueur2;
    }

    public function setJoueur2(User $joueur2): self
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

    public function getMilieux(): ?Card
    {
        return $this->milieux;
    }

    public function setMilieux(?Card $milieux): self
    {
        $this->milieux = $milieux;

        return $this;
    }

    public function getBank(): ?Card
    {
        return $this->bank;
    }

    public function setBank(?Card $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getMise(): ?int
    {
        return $this->mise;
    }

    public function setMise(int $mise): self
    {
        $this->mise = $mise;

        return $this;
    }
}
