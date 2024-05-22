<?php

namespace App\Entity;

use App\Repository\AvailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $depart_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $return_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price_per_day = null;

    #[ORM\Column]
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getDepartDate(): ?\DateTimeInterface
    {
        return $this->depart_date;
    }

    public function setDepartDate(\DateTimeInterface $depart_date): static
    {
        $this->depart_date = $depart_date;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(\DateTimeInterface $return_date): static
    {
        $this->return_date = $return_date;

        return $this;
    }

    public function getPricePerDay(): ?string
    {
        return $this->price_per_day;
    }

    public function setPricePerDay(string $price_per_day): static
    {
        $this->price_per_day = $price_per_day;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status? 'Available' : 'Not Available';
    }

    public function setStatus(string $status): static
    {
        $this->status = ($status === 'Available');

        return $this;
    }
}
