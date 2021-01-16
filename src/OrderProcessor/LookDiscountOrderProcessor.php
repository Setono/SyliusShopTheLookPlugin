<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\OrderProcessor;

use Setono\SyliusShopTheLookPlugin\LookMatcher\LookMatcherInterface;
use Setono\SyliusShopTheLookPlugin\Model\AdjustmentInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Repository\LookRepositoryInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Webmozart\Assert\Assert;

final class LookDiscountOrderProcessor implements OrderProcessorInterface
{
    private LookMatcherInterface $lookMatcher;

    private LookRepositoryInterface $lookRepository;

    private AdjustmentFactoryInterface $adjustmentFactory;

    public function __construct(
        LookMatcherInterface $lookMatcher,
        LookRepositoryInterface $lookRepository,
        AdjustmentFactoryInterface $adjustmentFactory
    ) {
        $this->lookMatcher = $lookMatcher;
        $this->lookRepository = $lookRepository;
        $this->adjustmentFactory = $adjustmentFactory;
    }

    public function process(OrderInterface $order): void
    {
        // Remove adjustments from previous processor execution
        // todo should be done in the adjustments clearer by Sylius I guess?
        $order->removeAdjustmentsRecursively(AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT);

        if ($order->isEmpty()) {
            return;
        }

        $looks = $this->lookRepository->findEnabled();
        foreach ($looks as $look) {
            $intersectedProductIds = $this->lookMatcher->match($order, $look);
            if (null === $intersectedProductIds) {
                continue;
            }

            $this->applyLookDiscountToOrderForProductIds($order, $look, $intersectedProductIds);
        }
    }

    private function applyLookDiscountToOrderForProductIds(OrderInterface $order, LookInterface $look, array $intersectedProductIds): void
    {
        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            /** @var ProductInterface|null $product */
            $product = $orderItem->getProduct();
            Assert::notNull($product);

            $productId = $product->getId();
            Assert::notNull($productId);

            if (in_array($productId, $intersectedProductIds, true)) {
                $currentDiscountAmount = abs($orderItem->getAdjustmentsTotal(AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT));

                // Restore original item total, without (another) look discount to correctly calculate (new) discount amount
                $originalOrderItemTotal = $orderItem->getTotal() + $currentDiscountAmount;

                $newDiscountAmount = (int) round($originalOrderItemTotal * $look->getDiscount(), 0);

                // Remove current discount (if exists) and apply new one only if it greater than current one
                if ($newDiscountAmount > $currentDiscountAmount) {
                    $orderItem->removeAdjustments(AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT);
                    $this->addAdjustment($orderItem, $look, $newDiscountAmount);
                }
            }
        }
    }

    private function addAdjustment(OrderItemInterface $orderItem, LookInterface $look, int $total): void
    {
        $adjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT,
            (string) $look->getName(),
            -1 * $total
        );
        $adjustment->setOriginCode((string) $look->getId());
        $orderItem->addAdjustment($adjustment);
    }
}
