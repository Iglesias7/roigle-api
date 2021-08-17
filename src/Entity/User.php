<?php

namespace App\Entity;

use App\Entity\Pret;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(message="Vous devez indiquer votre email")
     *
     * @Groups("Default")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Vous devez indiquer votre nom")
     *
     * @Groups("Default")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Vous devez indiquer votre prénom")
     *
     * @Groups("Default")
     */
    private $lastName;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Groups("Default")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups("Default")
     */
    private $isOpen = false;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups("Default")
     */
    private $reputation = 1;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups("Default")
     */
    private $qualityOfLife = "pauvre";

    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->cars = new ArrayCollection();
        $this->immobiliers = new ArrayCollection();
        $this->friendsSend = new ArrayCollection();
        $this->friendsSended = new ArrayCollection();
        $this->friendsReceived = new ArrayCollection();
        $this->friendsReceivedd = new ArrayCollection();
        $this->ceuxQueJeDois = new ArrayCollection();
        $this->ceuxQuiMeDoivent = new ArrayCollection();
        $this->demandeEnvoyer = new ArrayCollection();
        $this->demandeRecus = new ArrayCollection();
        $this->hand = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Groups("Default")
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followees",cascade={"persist"})
     *
     *
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Car", inversedBy="users",cascade={"persist"})
     *
     *@Groups("Default")
     */
    private $cars;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Immobilier", inversedBy="users",cascade={"persist"})
     *
     *@Groups("Default")
     */
    private $immobiliers;


    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups("Default")
     */
    private $isAdmin = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="friendsSended")
     * @JoinTable(name="send_user",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="send_id", referencedColumnName="id")}
     * )
     */
    private $friendsSend;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="friendsSend")
     */
    private $friendsSended;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="friendsReceivedd")
     * @JoinTable(name="received_user",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="received_id", referencedColumnName="id")}
     * )
     */
    private $friendsReceived;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="friendsReceived")
     * 
     */
    private $friendsReceivedd;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", inversedBy="userPrets",cascade={"persist"})
     * 
     * @JoinTable(name="prets",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="pret_id", referencedColumnName="id")}
     * )
     */
    private $ceuxQuiMeDoivent;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", inversedBy="userEmprunts",cascade={"persist"})
     * 
     * @JoinTable(name="emprunts",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="emprunts_id", referencedColumnName="id")}
     * )
     */
    private $ceuxQueJeDois;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", inversedBy="userPretsSend",cascade={"persist"})
     * 
     * @JoinTable(name="pretsSend",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="pretsSend_id", referencedColumnName="id")}
     * )
     */
    private $demandeEnvoyer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", inversedBy="userEmpruntsSend",cascade={"persist"})
     * 
     *  @JoinTable(name="empruntsSend",
     *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@JoinColumn(name="empruntsSend_id", referencedColumnName="id")}
     * )
     */
    private $demandeRecus;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Compte", cascade={"persist", "remove"})
     * 
     * @Groups("Default")
     */
    private $compte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="user")
     * 
     * @Groups("Default")
     */
    private $hand;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("Default")
     */
    private $nbCards = 0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Match", mappedBy="joueur1", cascade={"persist", "remove"})
     * 
     * 
     */
    private $match1;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Match", mappedBy="joueur2", cascade={"persist", "remove"})
     * 
     * 
     */
    private $match2;

    /**
     * @ORM\Column(type="boolean")
     * 
     * @Groups("Default")
     */
    private $aMoi = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("Default")
     */
    private $maxMise = 1000;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("Default")
     */
    private $nbTrophet = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="user", orphanRemoval=true)
     */
    private $votes;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function getReputation(): ?int
    {
        return $this->reputation;
    }

    public function setReputation(int $reputation): self
    {
        $this->reputation = $reputation;

        return $this;
    }

    public function getQualityOfLife(): string
    {
        if($this->getCars()->count() == 0 && $this->getImmobiliers()->count() == 0)
            return "pauvre";
        else if($this->getCars()->count() > 0 || $this->getImmobiliers()->count() > 0)
            return "riche";
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return string[]
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        if ($this->isAdmin) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): ?self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|self[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(self $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
        }

        return $this;
    }

    public function removeFollower(self $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
        }

        return $this;
    }

    /**
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->contains($car)) {
            $this->cars->removeElement($car);
        }

        return $this;
    }

    /**
     * @return Collection|Immobilier[]
     */
    public function getImmobiliers(): Collection
    {
        return $this->immobiliers;
    }

    public function addImmobilier(Immobilier $immobilier): self
    {
        if (!$this->immobiliers->contains($immobilier)) {
            $this->immobiliers[] = $immobilier;
        }

        return $this;
    }

    public function removeImmobilier(Immobilier $immobilier): self
    {
        if ($this->immobiliers->contains($immobilier)) {
            $this->immobiliers->removeElement($immobilier);
        }

        return $this;
    }

    /**
     * @Groups("Default")
     */
    public function getNbCars(): int
    {
        return $this->getCars()->count();
    }

    /**
     * @Groups("Default")
     */
    public function getNbImmobiliers(): int
    {
        return $this->getImmobiliers()->count();
    }

    /**
     * @return Collection|self[]
     */
    public function getFriendsSend(): Collection
    {
        return $this->friendsSend;
    }

    public function addFriendsSend(self $friendsSend): self
    {
        if (!$this->friendsSend->contains($friendsSend)) {
            $this->friendsSend[] = $friendsSend;
        }

        return $this;
    }

    public function removeFriendsSend(self $friendsSend): self
    {
        if ($this->friendsSend->contains($friendsSend)) {
            $this->friendsSend->removeElement($friendsSend);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriendsSended(): Collection
    {
        return $this->friendsSended;
    }

    public function addFriendsSended(self $friendsSended): self
    {
        if (!$this->friendsSended->contains($friendsSended)) {
            $this->friendsSended[] = $friendsSended;
            $friendsSended->addFriendsSend($this);
        }

        return $this;
    }

    public function removeFriendsSended(self $friendsSended): self
    {
        if ($this->friendsSended->contains($friendsSended)) {
            $this->friendsSended->removeElement($friendsSended);
            $friendsSended->removeFriendsSend($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriendsReceived(): Collection
    {
        return $this->friendsReceived;
    }

    public function addFriendsReceived(self $friendsReceived): self
    {
        if (!$this->friendsReceived->contains($friendsReceived)) {
            $this->friendsReceived[] = $friendsReceived;
        }

        return $this;
    }

    public function removeFriendsReceived(self $friendsReceived): self
    {
        if ($this->friendsReceived->contains($friendsReceived)) {
            $this->friendsReceived->removeElement($friendsReceived);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriendsReceivedd(): Collection
    {
        return $this->friendsReceivedd;
    }

    public function addFriendsReceivedd(self $friendsReceivedd): self
    {
        if (!$this->friendsReceivedd->contains($friendsReceivedd)) {
            $this->friendsReceivedd[] = $friendsReceivedd;
            $friendsReceivedd->addFriendsReceived($this);
        }

        return $this;
    }

    public function removeFriendsReceivedd(self $friendsReceivedd): self
    {
        if ($this->friendsReceivedd->contains($friendsReceivedd)) {
            $this->friendsReceivedd->removeElement($friendsReceivedd);
            $friendsReceivedd->removeFriendsReceived($this);
        }

        return $this;
    }

    //*************************************************** */

    /**
     * @return Collection|Pret[]
     */
    public function getCeuxQuiMeDoivent(): Collection
    {
        return $this->ceuxQuiMeDoivent;
    }

    public function addCeuxQuiMeDoivent(Pret $pret): self
    {
        if (!$this->ceuxQuiMeDoivent->contains($pret)) {
            $this->ceuxQuiMeDoivent[] = $pret;
        }

        return $this;
    }

    public function removeCeuxQuiMeDoivent(Pret $pret): self
    {
        if ($this->ceuxQuiMeDoivent->contains($pret)) {
            $this->ceuxQuiMeDoivent->removeElement($pret);
        }

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getCeuxQueJeDois(): Collection
    {
        return $this->ceuxQueJeDois;
    }

    public function addCeuQueJeDois(Pret $pret): self
    {
        if (!$this->ceuxQueJeDois->contains($pret)) {
            $this->ceuxQueJeDois[] = $pret;
        }

        return $this;
    }

    public function removeCeuxQueJeDois(Pret $pret): self
    {
        if ($this->ceuxQueJeDois->contains($pret)) {
            $this->ceuxQueJeDois->removeElement($pret);
        }

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getDemandesEnvoyer(): Collection
    {
        return $this->demandeEnvoyer;
    }

    public function addDemandeEnvoyer(Pret $pret): self
    {
        if (!$this->demandeEnvoyer->contains($pret)) {
            $this->demandeEnvoyer[] = $pret;
        }

        return $this;
    }

    public function removeDemandeEnvoyer(Pret $pret): self
    {
        if ($this->demandeEnvoyer->contains($pret)) {
            $this->demandeEnvoyer->removeElement($pret);
        }

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getDemandesRecus(): Collection
    {
        return $this->demandeRecus;
    }

    public function addDemandeRecus(Pret $pret): self
    {
        if (!$this->demandeRecus->contains($pret)) {
            $this->demandeRecus[] = $pret;
        }

        return $this;
    }

    public function removeDemandeRecus(Pret $pret): self
    {
        if ($this->demandeRecus->contains($pret)) {
            $this->demandeRecus->removeElement($pret);
        }

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * @return Collection|card[]
     */
    public function getHand(): Collection
    {
        return $this->hand;
    }

    public function addHand(card $hand): self
    {
        if (!$this->hand->contains($hand)) {
            $this->hand[] = $hand;
            $hand->setUser($this);
        }

        return $this;
    }

    public function removeHand(?card $hand): self
    {
        if ($this->hand->contains($hand)) {
            $this->hand->removeElement($hand);
            // set the owning side to null (unless already changed)
            if ($hand->getUser() === $this) {
                $hand->setUser(null);
            }
        }

        return $this;
    }

    public function getNbCards(): ?int
    {
        return $this->nbCards;
    }

    public function setNbCards(?int $nbCards): self
    {
        $this->nbCards = $nbCards;

        return $this;
    }

    public function getMatch1(): ?Match
    {
        return $this->match1;
    }

    public function setMatch1(Match $match1): self
    {
        $this->match1 = $match1;

        // set the owning side of the relation if necessary
        if ($match1->getJoueur1() !== $this) {
            $match1->setJoueur1($this);
        }

        return $this;
    }

    public function getMatch2(): ?Match
    {
        return $this->match2;
    }

    public function setMatch2(Match $match2): self
    {
        $this->match2 = $match2;

        // set the owning side of the relation if necessary
        if ($match2->getJoueur2() !== $this) {
            $match2->setJoueur2($this);
        }

        return $this;
    }

    public function getAMoi(): ?bool
    {
        return $this->aMoi;
    }

    public function setAMoi(bool $aMoi): self
    {
        $this->aMoi = $aMoi;

        return $this;
    }

    public function getMaxMise(): ?int
    {
        return $this->maxMise;
    }

    public function setMaxMise(?int $maxMise): self
    {
        $this->maxMise = $maxMise;

        return $this;
    }

    public function getNbTrophet(): ?int
    {
        return $this->nbTrophet;
    }

    public function setNbTrophet(?int $nbTrophet): self
    {
        $this->nbTrophet = $nbTrophet;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

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
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
            }
        }

        return $this;
    }

}
