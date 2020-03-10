<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

class Look implements LookInterface
{
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    protected int $id;

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

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(string $name): void
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
        $this->parts->add($part);
    }

    public function removePart(LookPartInterface $part): void
    {
        if ($this->hasPart($part)) {
            $this->parts->removeElement($part);
        }
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
        return $this->images->contains($image);
    }

    public function addImage(ImageInterface $image): void
    {
        $image->setOwner($this);
        $this->images->add($image);
    }

    public function removeImage(ImageInterface $image): void
    {
        if ($this->hasImage($image)) {
            $image->setOwner(null);
            $this->images->removeElement($image);
        }
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
