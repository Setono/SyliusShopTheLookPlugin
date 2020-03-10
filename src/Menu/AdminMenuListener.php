<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $submenu = $menu->getChild('catalog');
        if ($submenu instanceof ItemInterface) {
            $this->addChild($submenu);
        } else {
            $this->addChild($menu->getFirstChild());
        }
    }

    private function addChild(ItemInterface $item): void
    {
        $item
            ->addChild('looks', [
                'route' => 'setono_sylius_shop_the_look_admin_look_index',
            ])
            ->setLabel('setono_sylius_shop_the_look.ui.looks')
            ->setLabelAttribute('icon', 'images outline')
        ;
    }
}
