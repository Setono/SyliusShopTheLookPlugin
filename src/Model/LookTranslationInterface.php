<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface LookTranslationInterface extends ResourceInterface, SlugAwareInterface, TranslationInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
