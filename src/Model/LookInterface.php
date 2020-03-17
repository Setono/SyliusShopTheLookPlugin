<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface LookInterface extends ResourceInterface, TimestampableInterface, CodeAwareInterface, SlugAwareInterface, ImagesAwareInterface, TranslatableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    /**
     * @return Collection|LookPartInterface[]
     *
     * @psalm-return Collection<array-key, LookPartInterface>
     */
    public function getParts(): Collection;

    public function hasParts(): bool;

    public function hasPart(LookPartInterface $part): bool;

    public function addPart(LookPartInterface $part): void;

    public function removePart(LookPartInterface $part): void;
}
