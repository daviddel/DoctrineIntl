<?php

namespace DavidDel\DoctrineIntlBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DavidDelDoctrineIntlExtension extends ConfigurableExtension
{
    /**
     * {@inheritDoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $container->setParameter('david_del_doctrine_intl.translatable_subscriber.class',
            $config['translatable_subscriber']['class']);
        $container->setParameter('david_del_doctrine_intl.translatable_subscriber.translatable_fetch_method',
            $config['translatable_subscriber']['translatable_fetch_method']);
        $container->setParameter('david_del_doctrine_intl.translatable_subscriber.translation_fetch_method',
            $config['translatable_subscriber']['translation_fetch_method']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yml');
    }
}
