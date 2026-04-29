<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTime $start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual(propertyPath: 'start')]
    private ?\DateTime $end = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    private ?Item $item = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDayOfLoan(): int
    {
        $start = $this->start;
        $end = $this->end;

        $interval = $start->diff($end);

        return intval($interval->format("%a"));
    }
}
