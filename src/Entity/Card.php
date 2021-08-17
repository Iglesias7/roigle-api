<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
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
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("Default")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=3)
     * 
     * @Groups("Default")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("Default")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     * 
     * @Groups("Default")
     */
    private $faceUp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="hand")
     * 
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFaceUp(): ?bool
    {
        return $this->faceUp;
    }

    public function setFaceUp(bool $faceUp): self
    {
        $this->faceUp = $faceUp;

        return $this;
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
