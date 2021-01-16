<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Webmozart\Assert\Assert;

class Look implements LookInterface
{
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    protected ?int $id = null;

    protected ?string $code = null;

    protected float $discount = 0.0;

    protected bool $enabled = true;

    protected ?int $position = null;

    /**
     * @var Collection|LookPartInterface[]
     *
     * @psalm-var Collection<array-key, LookPartInterface>
     */
    protected Collection $parts;

    /**
     * @var Collection|ImageInterface[]
     *
     * @psalm-var Collection<array-key, ImageInterface>
     */
    protected Collection $images;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->parts = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getDisplayableDiscount(): float
    {
        return $this->getDiscount() * 100;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    public function setSlug(?string $slug): void
    {
        $this->getTranslation()->setSlug($slug);
    }

    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function hasParts(): bool
    {
        return !$this->parts->isEmpty();
    }

    public function hasPart(LookPartInterface $part): bool
    {
        return $this->parts->contains($part);
    }

    public function addPart(LookPartInterface $part): void
    {
        if ($this->hasPart($part)) {
            return;
        }

        $this->parts->add($part);
        $part->setLook($this);
    }

    public function removePart(LookPartInterface $part): void
    {
        if (!$this->hasPart($part)) {
            return;
        }

        $this->parts->removeElement($part);
        $part->setLook(null);
    }

    public function getProducts(): Collection
    {
        $products = new ArrayCollection();
        foreach ($this->parts as $part) {
            foreach ($part->getProducts() as $product) {
                /** @psalm-suppress InvalidArgument */
                $products->add($product);
            }
        }

        return $products;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getImagesByType(string $type): Collection
    {
        return $this->images->filter(static function (ImageInterface $image) use ($type): bool {
            return $type === $image->getType();
        });
    }

    public function hasImages(): bool
    {
        return !$this->images->isEmpty();
    }

    public function hasImage(ImageInterface $image): bool
    {
        Assert::isInstanceOf($image, LookImageInterface::class);

        return $this->images->contains($image);
    }

    public function addImage(ImageInterface $image): void
    {
        Assert::isInstanceOf($image, LookImageInterface::class);
        if ($this->hasImage($image)) {
            return;
        }

        $image->setLook($this);
        $this->images->add($image);
    }

    public function removeImage(ImageInterface $image): void
    {
        Assert::isInstanceOf($image, LookImageInterface::class);
        if (!$this->hasImage($image)) {
            return;
        }

        $image->setLook(null);
        $this->images->removeElement($image);
    }

    public function getTranslation(?string $locale = null): LookTranslationInterface
    {
        /** @var LookTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(): LookTranslation
    {
        return new LookTranslation();
    }
}
