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
use Sylius\Component\Order\Model\OrderItemUnitInterface;
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

            if (!in_array($productId, $intersectedProductIds, true)) {
                continue;
            }

            foreach ($orderItem->getUnits() as $orderItemUnit) {
                $currentDiscountAmount = abs($orderItemUnit->getAdjustmentsTotal(AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT));

                // Restore original unit total, without (another) look discount to correctly calculate (new) discount amount
                $originalUnitTotal = $orderItemUnit->getTotal() + $currentDiscountAmount;
                $newDiscountAmount = (int) round($originalUnitTotal * $look->getDiscount(), 0);

                // Remove current discount (if exists) and apply new one only if it greater than current one
                if ($newDiscountAmount > $currentDiscountAmount) {
                    $orderItemUnit->removeAdjustments(AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT);
                    $this->addAdjustment($orderItemUnit, $look, $newDiscountAmount);
                }
            }
        }
    }

    private function addAdjustment(OrderItemUnitInterface $orderItemUnit, LookInterface $look, int $unitAdjustmentAmount): void
    {
        $code = $look->getCode();
        Assert::notNull($code);

        $name = $look->getName();
        Assert::notNull($name);

        $adjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::ORDER_UNIT_LOOK_ADJUSTMENT,
            $name,
            -1 * $unitAdjustmentAmount
        );

        $adjustment->setOriginCode($code);
        $orderItemUnit->addAdjustment($adjustment);
    }
}
