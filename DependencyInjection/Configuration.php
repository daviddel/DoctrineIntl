<?php

namespace DavidDel\DoctrineIntlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('david_del_doctrine_intl');

        $rootNode
            ->children()
                ->arrayNode('translatable_subscriber')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue('DavidDel\\DoctrineIntlBundle\\Model\\ORM\\TranslatableSubscriber')
                        ->end()
                        ->scalarNode('translatable_fetch_method')
                            ->defaultValue('LAZY')
                        ->end()
                        ->scalarNode('translation_fetch_method')
                            ->defaultValue('LAZY')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
