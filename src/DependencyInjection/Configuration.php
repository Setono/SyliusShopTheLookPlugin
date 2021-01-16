<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\DependencyInjection;

use Setono\SyliusShopTheLookPlugin\Doctrine\ORM\LookImageRepository;
use Setono\SyliusShopTheLookPlugin\Doctrine\ORM\LookPartRepository;
use Setono\SyliusShopTheLookPlugin\Doctrine\ORM\LookRepository;
use Setono\SyliusShopTheLookPlugin\Form\Type\LookImageType;
use Setono\SyliusShopTheLookPlugin\Form\Type\LookPartType;
use Setono\SyliusShopTheLookPlugin\Form\Type\LookTranslationType;
use Setono\SyliusShopTheLookPlugin\Form\Type\LookType;
use Setono\SyliusShopTheLookPlugin\Model\Look;
use Setono\SyliusShopTheLookPlugin\Model\LookImage;
use Setono\SyliusShopTheLookPlugin\Model\LookImageInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookPart;
use Setono\SyliusShopTheLookPlugin\Model\LookPartInterface;
use Setono\SyliusShopTheLookPlugin\Model\LookTranslation;
use Setono\SyliusShopTheLookPlugin\Model\LookTranslationInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_shop_the_look');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('look')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Look::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(LookRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(LookType::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue(LookTranslation::class)->cannotBeEmpty()->end()
                                                ->scalarNode('interface')->defaultValue(LookTranslationInterface::class)->cannotBeEmpty()->end()
                                                ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                                ->scalarNode('repository')->cannotBeEmpty()->end()
                                                ->scalarNode('form')->defaultValue(LookTranslationType::class)->cannotBeEmpty()->end()
                                                ->scalarNode('factory')->defaultValue(TranslatableFactory::class)->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('look_part')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(LookPart::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(LookPartInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(LookPartRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(LookPartType::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('look_image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(LookImage::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(LookImageInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(LookImageRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(LookImageType::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
