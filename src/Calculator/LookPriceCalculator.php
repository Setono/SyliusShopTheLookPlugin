<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Calculator;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Webmozart\Assert\Assert;

final class LookPriceCalculator implements LookPriceCalculatorInterface
{
    private ProductVariantResolverInterface $productVariantResolver;

    private ProductVariantPriceCalculatorInterface $productVariantPriceCalculator;

    public function __construct(
        ProductVariantResolverInterface $productVariantResolver,
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator
    ) {
        $this->productVariantResolver = $productVariantResolver;
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    public function calculatePrice(LookInterface $look, array $context): int
    {
        return $this->calculateCallableTotal(
            $look,
            [$this->productVariantPriceCalculator, 'calculate'],
            $context
        );
    }

    public function calculateDiscount(LookInterface $look, array $context): int
    {
        return $this->calculateCallableTotal($look, function (ProductVariantInterface $productVariant, array $context) use ($look): int {
            $itemPrice = $this->productVariantPriceCalculator->calculate(
                $productVariant,
                $context
            );

            return (int) round($itemPrice * $look->getDiscount());
        }, $context);
    }

    public function calculateTotal(LookInterface $look, array $context): int
    {
        return $this->calculatePrice($look, $context) - $this->calculateDiscount($look, $context);
    }

    /**
     * @todo Move to separate calculator?
     *
     * We need this as far as when we pass (variant|sylius_calculate_price * discount)
     * to sylius_convert_money, it not round that float, but just floor it as
     * sylius_convert_money expect int at its first argument and twig converts
     * that way for some reason
     */
    public function calculateVariantDiscount(ProductVariantInterface $productVariant, LookInterface $look, array $context): int
    {
        $itemPrice = $this->productVariantPriceCalculator->calculate(
            $productVariant,
            $context
        );

        return (int) round($itemPrice * $look->getDiscount());
    }

    private function calculateCallableTotal(LookInterface $look, callable $fn, array $context): int
    {
        $total = 0;
        foreach ($look->getParts() as $lookPart) {
            foreach ($lookPart->getProducts() as $product) {
                /** @var ProductVariantInterface|null $productVariant */
                $productVariant = $this->productVariantResolver->getVariant($product);
                Assert::notNull($productVariant);

                $total += call_user_func_array($fn, [$productVariant, $context]);

                // We need only price of first product
                // If user will select other than first product, that
                // will be recalculated by client-size script
                break;
            }
        }

        return $total;
    }
}
