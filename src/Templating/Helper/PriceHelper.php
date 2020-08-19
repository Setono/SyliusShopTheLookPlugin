<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Templating\Helper;

use Setono\SyliusShopTheLookPlugin\Calculator\LookPriceCalculatorInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Templating\Helper\Helper;

class PriceHelper extends Helper implements PriceHelperInterface
{
    private LookPriceCalculatorInterface $lookPriceCalculator;

    public function __construct(LookPriceCalculatorInterface $lookPriceCalculator)
    {
        $this->lookPriceCalculator = $lookPriceCalculator;
    }

    public function getPrice(LookInterface $look, array $context): int
    {
        return $this->lookPriceCalculator->calculatePrice($look, $context);
    }

    public function getDiscount(LookInterface $look, array $context): int
    {
        return $this->lookPriceCalculator->calculateDiscount($look, $context);
    }

    public function getTotal(LookInterface $look, array $context): int
    {
        return $this->lookPriceCalculator->calculateTotal($look, $context);
    }

    public function getVariantDiscount(ProductVariantInterface $productVariant, LookInterface $look, array $context): int
    {
        return $this->lookPriceCalculator->calculateVariantDiscount($productVariant, $look, $context);
    }

    public function getName(): string
    {
        return 'setono_look_calculate_price';
    }
}
