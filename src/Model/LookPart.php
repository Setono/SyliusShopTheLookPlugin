<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;

class LookPart implements LookPartInterface
{
    protected int $id;

    protected string $name;

    protected LookInterface $look;

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

    public function getLook(): ?LookInterface
    {
        return $this->look;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }
}
