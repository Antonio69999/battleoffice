<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders', cascade: ['persist', 'remove'])]
    #[Groups(['order'])]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'orders', cascade: ['persist'])]
    #[Groups(['order'])]
    private ?Adress $bilingAdress = null;

    #[ORM\ManyToOne(inversedBy: 'ShippingOrders', cascade: ['persist'])]
    #[Groups(['order'])]
    private ?Adress $shippingAdress = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'product')]
    #[Groups(['order'])]
    private Collection $products;

    #[ORM\Column(length: 255)]
    #[Groups(['order'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Payment $payment = null;

    #[ORM\Column(nullable: true)]
    private ?int $apiOrderId = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getBilingAdress(): ?Adress
    {
        return $this->bilingAdress;
    }

    public function setBilingAdress(?Adress $bilingAdress): static
    {
        $this->bilingAdress = $bilingAdress;

        return $this;
    }

    public function getShippingAdress(): ?Adress
    {
        return $this->shippingAdress;
    }

    public function setShippingAdress(?Adress $shippingAdress): static
    {
        $this->shippingAdress = $shippingAdress;

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

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getApiOrderId(): ?int
    {
        return $this->apiOrderId;
    }

    public function setApiOrderId(?int $apiOrderId): static
    {
        $this->apiOrderId = $apiOrderId;

        return $this;
    }
}