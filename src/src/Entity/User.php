<?php

namespace App\Entity;

use App\Model\TimeBuyerInterface;
use App\Model\TimeLoggerInterface;
use App\Model\TimeLoggerTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TimeLoggerInterface, TimeBuyerInterface
{

    use TimeLoggerTrait;

    const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^((\+98|0)9\d{9})$/",
        message: "You have to type phone number only"
    )]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'owner', targetEntity: Locker::class, cascade: ['persist', 'remove'])]
    private ?Locker $locker;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;


    #[ORM\Column(type:'datetime_immutable', nullable: true)]
    protected \DateTimeImmutable $shoppedAt;

    #[ORM\ManyToOne(inversedBy: 'member')]
    private ?Membership $membership = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfClasses = null;

    #[ORM\Column(nullable: true)]
    private ?int $credit = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getLocker(): ?Locker
    {
        return $this->locker;
    }

    public function setLocker(?Locker $locker): self
    {
        // unset the owning side of the relation if necessary
        if ($locker === null && $this->locker !== null) {
            $this->locker->setOwner(null);
        }

        // set the owning side of the relation if necessary
        if ($locker !== null && $locker->getOwner() !== $this) {
            $locker->setOwner($this);
        }

        $this->locker = $locker;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }


    public function getShoppedAt(): \DateTimeImmutable
    {
        return $this->shoppedAt;
    }

    public function setShoppedAt(\DateTimeImmutable $shoppedAt): TimeBuyerInterface
    {
        $this->shoppedAt = $shoppedAt;
        return $this;
    }

    public function getMembership(): ?Membership
    {
        return $this->membership;
    }

    public function setMembership(?Membership $membership): self
    {
        $this->membership = $membership;

        return $this;
    }

    public function getNumberOfClasses(): ?int
    {
        return $this->numberOfClasses;
    }

    public function setNumberOfClasses(int $numberOfClasses): self
    {
        $this->numberOfClasses = $numberOfClasses;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(?int $credit): self
    {
        $this->credit = $credit;

        return $this;
    }
}
