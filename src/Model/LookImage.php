<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Model;

use Sylius\Component\Core\Model\Image;

class LookImage extends Image implements LookImageInterface
{
    public function setLook(?LookInterface $look): void
    {
        $this->setOwner($look);
    }
}
