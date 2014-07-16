<?php

namespace JsonSpec\Behat;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class Extension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'json_spec';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
//            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('excluded_keys')
                    ->addDefaultChildrenIfNoneSet()
                    ->prototype('scalar')->defaultValue('id')->end()
                    ->example(array('id, created_at'))
                ->end()
            ->end()
        ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setParameter('json_spec.excluded_keys', $config['excluded_keys']);

        $this->registerHelpers($container);
        $this->registerArgumentResolvers($container);
        $this->registerJsonProvider($container);
        $this->registerContextInitializers($container);
    }

    public function registerHelpers(ContainerBuilder $container)
    {
        $definition = new Definition('Seld\\JsonLint\\JsonParser');
        $container->setDefinition('json_spec.helper.json_parser', $definition);

        $definition = new Definition('JsonSpec\\Helper\\JsonHelper', array(
            new Reference('json_spec.helper.json_parser')
        ));
        $container->setDefinition('json_spec.helper.json_helper', $definition);

        $definition = new Definition('JsonSpec\\Helper\\MemoryHelper');
        $container->setDefinition('json_spec.helper.memory_helper', $definition);

        $definition = new Definition('JsonSpec\\MatcherOptionsFactory', array(
            '%json_spec.excluded_keys%'
        ));
        $container->setDefinition('json_spec.matcher_options_factory', $definition);
    }

    private function registerArgumentResolvers(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Context\\Argument\JsonSpecArgumentResolver', array(
            new Reference('json_spec.matcher_options_factory'),
            new Reference('json_spec.helper.memory_helper'),
            new Reference('json_spec.helper.json_helper'),
            new Reference('json_spec.provider.json_provider'),
        ));
        $definition->addTag(ContextExtension::ARGUMENT_RESOLVER_TAG);
        $container->setDefinition('json_spec.argument_resolver', $definition);
    }

    private function registerJsonProvider(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Consumer\\JsonConsumer');
        $container->setDefinition('json_spec.provider.json_provider', $definition);
    }

    private function registerContextInitializers(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Context\\Initializer\\JsonSpecInitializer', array(
            new Reference('json_spec.provider.json_provider'),
        ));
        $definition->addTag(ContextExtension::INITIALIZER_TAG);
        $container->setDefinition('json_spec.context_initializer', $definition);
    }

}
