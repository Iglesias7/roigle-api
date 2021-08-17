<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("Default")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * 
     * @Groups("Default")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("Default")
     */
    private $timestamp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     * 
     * @Groups("Default")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("Default")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="post", orphanRemoval=true)
     * 
     * 
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="parent", orphanRemoval=true)
     * 
     * 
     */
    private $responses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", cascade={"persist", "remove"})
     */
    private $parent;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

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

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setPost($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getPost() === $this) {
                $vote->setPost(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Post[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Post $post): self
    {
        if (!$this->responses->contains($post)) {
            $this->responses[] = $post;
        }

        return $this;
    }

    public function removeResponse(Post $post): self
    {
        if ($this->responses->contains($post)) {
            $this->responses->removeElement($post);
        }

        return $this;
    }

    /**
     * @Groups("Default")
     */
    public function getnbResponses(): ?int
    {
        return count($this->responses);
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function score (): ?int
    {
        return array_sum($this->votes->toArray()->map(function(Vote $v) { 
                                            return $v->getUpdown(); 
                                        })
        );
    }

    public function hightScore(): ?int
    {
        return max($this->score, count($this->responses) > 0 ? max($this->responses->toArray()->map(function(Post $post) {return $post->score(); })) : $this->score());
    }
}
