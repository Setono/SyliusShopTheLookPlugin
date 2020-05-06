<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\LookMatcher;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface as BaseOrderItemInterface;
use Webmozart\Assert\Assert;

class LookMatcher implements LookMatcherInterface
{
    public function match(OrderInterface $order, LookInterface $look): ?array
    {
        $intersections = [];

        $orderProductsIds = array_unique($order->getItems()->map(static function (BaseOrderItemInterface $orderItem) {
            Assert::isInstanceOf($orderItem, OrderItemInterface::class);

            $product = $orderItem->getProduct();
            Assert::notNull($product);

            return $product->getId();
        })->toArray());

        foreach ($look->getParts() as $part) {
            $partProductsIds = $part->getProducts()->map(static function (ProductInterface $product) {
                return $product->getId();
            })->toArray();

            $intersection = array_intersect($partProductsIds, $orderProductsIds);
            if ([] === $intersection) {
                return null;
            }

            $intersections = array_merge($intersections, $intersection);
        }

        return $intersections;
    }
}
