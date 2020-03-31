<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Factory;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookPartInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface LookPartFactoryInterface extends FactoryInterface
{
    public function createNew(): LookPartInterface;

    public function createForLook(LookInterface $look): LookPartInterface;
}
