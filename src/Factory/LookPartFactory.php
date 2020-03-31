<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Factory;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookPartInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class LookPartFactory implements LookPartFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): LookPartInterface
    {
        /** @var LookPartInterface $lookPart */
        $lookPart = $this->decoratedFactory->createNew();

        return $lookPart;
    }

    public function createForLook(LookInterface $look): LookPartInterface
    {
        $lookPart = $this->createNew();
        $lookPart->setLook($look);

        return $lookPart;
    }
}
