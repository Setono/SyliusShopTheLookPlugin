<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Calculator;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Webmozart\Assert\Assert;

final class LookPriceCalculator implements LookPriceCalculatorInterface
{
    /** @var ProductVariantResolverInterface */
    private $productVariantResolver;

    /** @var ProductVariantPriceCalculatorInterface */
    private $productVariantPriceCalculator;

    public function __construct(
        ProductVariantResolverInterface $productVariantResolver,
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator
    ) {
        $this->productVariantResolver = $productVariantResolver;
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    public function calculate(LookInterface $look, array $context): int
    {
        Assert::keyExists($context, 'channel');

        $channel = $context['channel'];
        Assert::isInstanceOf($channel, ChannelInterface::class);

        $price = 0;
        foreach ($look->getParts() as $lookPart) {
            foreach ($lookPart->getProducts() as $product) {
                /** @var ProductVariantInterface|null $productVariant */
                $productVariant = $this->productVariantResolver->getVariant($product);
                Assert::notNull($productVariant);

                $price += $this->productVariantPriceCalculator->calculate(
                    $productVariant,
                    $context
                );

                // We need only price of first product
                // @todo Create LookPartProductResolver to be able to update price based on selected products at parts
                break;
            }
        }

        return $price;
    }
}
