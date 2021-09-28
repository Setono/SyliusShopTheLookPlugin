<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Calculator;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

interface LookPriceCalculatorInterface
{
    public function calculatePrice(LookInterface $look, array $context): int;

    public function calculateDiscount(LookInterface $look, array $context): int;

    public function calculateTotal(LookInterface $look, array $context): int;

    public function calculateVariantDiscount(ProductVariantInterface $productVariant, LookInterface $look, array $context): int;
}
