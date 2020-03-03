<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\DependencyInjection;

use Exception;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusShopTheLookExtension extends AbstractResourceExtension
{
    /**
     * @param array<mixed> $config
     *
     * @throws Exception
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->registerResources('setono_sylius_shop_the_look', $config['driver'], $config['resources'], $container);

        $loader->load('services.xml');
    }
}
