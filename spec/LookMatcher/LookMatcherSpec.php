<?php

namespace spec\Setono\SyliusShopTheLookPlugin\LookMatcher;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Setono\SyliusShopTheLookPlugin\LookMatcher\LookMatcherInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookPartInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;

class LookMatcherSpec extends ObjectBehavior
{
    function it_is_a_look_matcher(): void
    {
        $this->shouldBeAnInstanceOf(LookMatcherInterface::class);
    }

    function it_matches(
        OrderInterface $order1,
        OrderInterface $order2,
        OrderItemInterface $orderItem1,
        OrderItemInterface $orderItem2,
        OrderItemInterface $orderItem3,
        OrderItemInterface $orderItem4,
        LookInterface $look,
        LookPartInterface $lookPart1,
        LookPartInterface $lookPart2,
        ProductInterface $product1,
        ProductInterface $product2,
        ProductInterface $product3,
        ProductInterface $product4
    ): void {
        $order1->getItems()->willReturn(new ArrayCollection([
            $orderItem1->getWrappedObject(),
            $orderItem3->getWrappedObject(),
        ]));
        $order2->getItems()->willReturn(new ArrayCollection([
            $orderItem2->getWrappedObject(),
            $orderItem3->getWrappedObject(),
            $orderItem4->getWrappedObject(),
        ]));
        $orderItem1->getProduct()->willReturn($product1);
        $orderItem2->getProduct()->willReturn($product2);
        $orderItem3->getProduct()->willReturn($product3);
        $orderItem4->getProduct()->willReturn($product4);

        $product1->getId()->willReturn(1);
        $product2->getId()->willReturn(2);
        $product3->getId()->willReturn(3);
        $product4->getId()->willReturn(4);

        $look->getParts()->willReturn(new ArrayCollection([
            $lookPart1->getWrappedObject(),
            $lookPart2->getWrappedObject(),
        ]));

        $lookPart1->getProducts()->willReturn(new ArrayCollection([
            $product1->getWrappedObject(),
        ]));

        $lookPart2->getProducts()->willReturn(new ArrayCollection([
            $product2->getWrappedObject(),
            $product3->getWrappedObject(),
            $product4->getWrappedObject(),
        ]));

        $this->match($order1, $look)->shouldReturn([1, 3]);
        $this->match($order2, $look)->shouldReturn(null);
    }
}
