<?php

namespace JsonSpec\Behat;

use Behat\Behat\Extension\ExtensionInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements ExtensionInterface
{
    /**
     * @inheritdoc
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('json_directory')->defaultNull()->end()
                ->arrayNode('excluded_keys')
                    ->addDefaultChildrenIfNoneSet()
                    ->prototype('scalar')->defaultValue('id')->end()
            ->end()
        ->end();
    }

    /**
     * @inheritdoc
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setParameter('json_spec.excluded_keys', $config['excluded_keys']);
        $container->setParameter('json_spec.json_directory', $config['json_directory']);
        // replace buggy definition dispatcher with fixes one
        $container->setParameter('behat.definition.dispatcher.class', 'JsonSpec\\Behat\\Definition\\DefinitionDispatcher');

        $this->registerHelpers($container);
        $this->registerJsonProvider($container);
        $this->registerContextInitializers($container);
    }

    /**
     * @inheritdoc
     */
    public function getCompilerPasses()
    {
        return array();
    }

    private function registerHelpers(ContainerBuilder $container)
    {
        $definition = new Definition('Seld\\JsonLint\\JsonParser');
        $container->setDefinition('json_spec.helper.json_parser', $definition);

        $definition = new Definition('JsonSpec\\Helper\\JsonHelper', array(
            new Reference('json_spec.helper.json_parser')
        ));
        $container->setDefinition('json_spec.helper.json_helper', $definition);

        $definition = new Definition('JsonSpec\\Helper\\MemoryHelper');
        $container->setDefinition('json_spec.helper.memory_helper', $definition);

        $definition = new Definition('JsonSpec\\Helper\\FileHelper', array(
            '%json_spec.json_directory%'
        ));
        $container->setDefinition('json_spec.helper.file_helper', $definition);

        $definition = new Definition('JsonSpec\\MatcherOptionsFactory', array(
            '%json_spec.excluded_keys%'
        ));
        $container->setDefinition('json_spec.matcher_options_factory', $definition);

        $definition = new Definition('JsonSpec\\JsonSpecMatcher', array(
            new Reference('json_spec.helper.json_helper'),
            new Reference('json_spec.matcher_options_factory'),
        ));
        $container->setDefinition('json_spec.matcher', $definition);
    }

    private function registerJsonProvider(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Consumer\\JsonConsumer');
        $container->setDefinition('json_spec.provider.json_provider', $definition);
    }

    private function registerContextInitializers(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Context\\Initializer\\JsonConsumerAwareInitializer', array(
            new Reference('json_spec.provider.json_provider'),
        ));
        $definition->addTag('behat.context.initializer');
        $container->setDefinition('json_spec.json_consumer_aware_initializer', $definition);

        $definition = new Definition('JsonSpec\\Behat\\Context\\Initializer\\JsonSpecContextInitializer', array(
            new Reference('json_spec.provider.json_provider'),
            new Reference('json_spec.matcher'),
            new Reference('json_spec.helper.json_helper'),
            new Reference('json_spec.helper.file_helper'),
            new Reference('json_spec.helper.memory_helper'),
        ));
        $definition->addTag('behat.context.initializer');
        $container->setDefinition('json_spec.context_initializer', $definition);
    }

}
