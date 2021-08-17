<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("Default")
     */
    private $post;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("Default")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups("Default")
     */
    private $updown;

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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

    public function getUpdown(): ?int
    {
        return $this->updown;
    }

    public function setUpdown(int $updown): self
    {
        $this->updown = $updown;

        return $this;
    }
}
