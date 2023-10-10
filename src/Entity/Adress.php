<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adressLine = null;

    #[ORM\Column(length: 255)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'idBilingAdress', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'idShippingAdress', targetEntity: Order::class)]
    private Collection $ShippingOrders;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adressLine2 = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->ShippingOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdressLine(): ?string
    {
        return $this->adressLine;
    }

    public function setAdressLine(string $adressLine): static
    {
        $this->adressLine = $adressLine;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setIdBilingAdress($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getIdBilingAdress() === $this) {
                $order->setIdBilingAdress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getShippingOrders(): Collection
    {
        return $this->ShippingOrders;
    }

    public function addShippingOrder(Order $shippingOrder): static
    {
        if (!$this->ShippingOrders->contains($shippingOrder)) {
            $this->ShippingOrders->add($shippingOrder);
            $shippingOrder->setIdShippingAdress($this);
        }

        return $this;
    }

    public function removeShippingOrder(Order $shippingOrder): static
    {
        if ($this->ShippingOrders->removeElement($shippingOrder)) {
            // set the owning side to null (unless already changed)
            if ($shippingOrder->getIdShippingAdress() === $this) {
                $shippingOrder->setIdShippingAdress(null);
            }
        }

        return $this;
    }

    public function getAdressLine2(): ?string
    {
        return $this->adressLine2;
    }

    public function setAdressLine2(?string $adressLine2): static
    {
        $this->adressLine2 = $adressLine2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
