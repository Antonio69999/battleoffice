<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Client $id_client = null;

    #[ORM\OneToOne(inversedBy: 'orders', cascade: ['persist', 'remove'])]
    private ?Payment $idPayment = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Adress $idBilingAdress = null;

    #[ORM\ManyToOne(inversedBy: 'ShippingOrders')]
    private ?Adress $idShippingAdress = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'product')]
    private Collection $products;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    private $adress;
    private $client;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?Client
    {
        return $this->id_client;
    }

    public function setIdClient(?Client $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getIdPayment(): ?Payment
    {
        return $this->idPayment;
    }

    public function setIdPayment(?Payment $idPayment): static
    {
        $this->idPayment = $idPayment;

        return $this;
    }

    public function getIdBilingAdress(): ?Adress
    {
        return $this->idBilingAdress;
    }

    public function setIdBilingAdress(?Adress $idBilingAdress): static
    {
        $this->idBilingAdress = $idBilingAdress;

        return $this;
    }

    public function getIdShippingAdress(): ?Adress
    {
        return $this->idShippingAdress;
    }

    public function setIdShippingAdress(?Adress $idShippingAdress): static
    {
        $this->idShippingAdress = $idShippingAdress;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addProduct($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeProduct($this);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
