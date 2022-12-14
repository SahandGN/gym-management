<?php

namespace App\Entity;

use App\Model\LockerOwnerInterface;
use App\Model\TimeLoggerInterface;
use App\Model\TimeLoggerTrait;
use App\Repository\LockerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LockerRepository::class)]
class Locker implements TimeLoggerInterface
{

    use TimeLoggerTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isEmpty = null;

    #[ORM\OneToOne(inversedBy: 'locker', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private ?User $owner = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function isIsEmpty(): ?bool
    {
        return $this->isEmpty;
    }

    public function setIsEmpty(?bool $isEmpty): self
    {
        $this->isEmpty = $isEmpty;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
//
//    public function getLockerOwner(): User
//    {
//
//    }
//
//    public function setLockerOwner(): User
//    {
//
//    }
}
