<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Sylius\Component\Resource\Model\AbstractTranslation;
use Webmozart\Assert\Assert;

class LookTranslation extends AbstractTranslation implements LookTranslationInterface
{
    protected ?int $id = null;

    protected ?string $name = null;

    protected ?string $description = null;

    protected ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        Assert::notNull($slug, 'The slug cannot be null');

        $this->slug = $slug;
    }
}
