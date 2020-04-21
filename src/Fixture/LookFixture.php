<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class LookFixture extends AbstractResourceFixture
{
    public function getName(): string
    {
        return 'setono_look';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $lookNode = $resourceNode->children();
        $lookNode->scalarNode('code')->cannotBeEmpty();
        $lookNode->floatNode('discount');
        $lookNode->booleanNode('enabled');
        $lookNode->integerNode('position');
        $lookNode->scalarNode('name')->cannotBeEmpty();
        $lookNode->scalarNode('slug')->cannotBeEmpty();
        $lookNode->scalarNode('description')->cannotBeEmpty();
        $lookNode->variableNode('translations')->cannotBeEmpty()->defaultValue([]);
        $lookNode->variableNode('images')->cannotBeEmpty()->defaultValue([]);

        $partsNode = $lookNode->arrayNode('parts');
        $partsNodePrototype = $partsNode->arrayPrototype();
        $partsNodePrototype->beforeNormalization()->ifString()
            ->then(static function (string $val): array {
                return ['name' => $val];
            })
        ;
        $partsNodePrototypeChildren = $partsNodePrototype->children();
        $partsNodePrototypeChildren->scalarNode('name')->cannotBeEmpty();
        $partsNodePrototypeChildren->integerNode('position');
        $partsNodePrototypeChildren->variableNode('products')->cannotBeEmpty()->defaultValue(3);
    }
}
