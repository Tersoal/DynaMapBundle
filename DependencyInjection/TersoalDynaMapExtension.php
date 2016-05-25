<?php

namespace Tersoal\DynaMapBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TersoalDynaMapExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!is_array($config['field_white_list'])) {
            $config['field_white_list'] = array($config['field_white_list']);
        }

        if (!is_array($config['field_black_list'])) {
            $config['field_black_list'] = array($config['field_black_list']);
        }

        $container->setParameter('tersoal.dyna_map.master_entity', $config['master_entity']);
        $container->setParameter('tersoal.dyna_map.field_entity', $config['field_entity']);
        $container->setParameter('tersoal.dyna_map.fields.white_list', $config['field_white_list']);
        $container->setParameter('tersoal.dyna_map.fields.black_list', $config['field_black_list']);
        $container->setParameter('tersoal.dyna_map.model.route.parameter', $config['route_parameter']);
    }
}
