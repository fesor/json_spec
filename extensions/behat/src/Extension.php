<?php

namespace JsonSpec\Behat;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use \Behat\Testwork\ServiceContainer\Extension as BehatExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements BehatExtension
{

    /**
     * @inheritdoc
     */
    public function getConfigKey()
    {
        return 'json_spec';
    }

    /**
     * @inheritdoc
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('excluded_keys')
                ->prototype('scalar')
                    ->example(array('id', 'created_at'))
                    ->end()
                ->defaultValue(array('id', 'created_at', 'updated_at'))
                ->end()
            ->scalarNode('json_directory')->defaultNull()->end()
        ->end();
    }

    /**
     * @inheritdoc
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setParameter('json_spec.excluded_keys', $config['excluded_keys']);
        $container->setParameter('json_spec.json_directory', $config['json_directory']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/Resources/services'));
        $loader->load('services.yml');
    }

    /**
     * @inheritdoc
     */
    public function initialize(ExtensionManager $extensionManager) {}

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container) {}

}
