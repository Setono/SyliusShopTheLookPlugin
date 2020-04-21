<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;

class LookPart implements LookPartInterface
{
    protected ?int $id = null;

    protected ?string $name = null;

    protected ?int $position = null;

    protected ?LookInterface $look = null;

    /**
     * @var Collection|ProductInterface[]
     *
     * @psalm-var Collection<array-key, ProductInterface>
     */
    protected Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getLook(): ?LookInterface
    {
        return $this->look;
    }

    public function setLook(?LookInterface $look): void
    {
        if ($look === $this->look) {
            return;
        }

        if (null !== $this->look) {
            $this->look->removePart($this);
        }

        $this->look = $look;

        if (null !== $look) {
            $look->addPart($this);
        }
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function hasProducts(): bool
    {
        return !$this->products->isEmpty();
    }

    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    public function addProduct(ProductInterface $product): void
    {
        if ($this->hasProduct($product)) {
            return;
        }

        $this->products->add($product);
    }

    public function removeProduct(ProductInterface $product): void
    {
        if (!$this->hasProduct($product)) {
            return;
        }

        $this->products->removeElement($product);
    }
}
