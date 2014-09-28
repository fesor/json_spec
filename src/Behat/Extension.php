<?php

namespace JsonSpec\Behat;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use \Behat\Testwork\ServiceContainer\Extension as BehatExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Compiler\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements BehatExtension
{

    const JSON_PROVIDER_TAG = 'json_spec.json_provider';

    private $webApiExtensionClassname;

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

        $this->registerHelpers($container);
        $this->provideWebApiIntegration($container);
        $this->registerJsonProvider($container);
        $this->registerJsonProviderVoter($container);
        $this->registerContextInitializers($container);
    }

    /**
     * @inheritdoc
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        $webApiExtension = $extensionManager->getExtension('web_api');
        if (is_object($webApiExtension)) {
            $this->webApiExtensionClassname = get_class($webApiExtension);
        }
    }

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container) {}

    /**
     * @param ContainerBuilder $container
     */
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

    /**
     * @param ContainerBuilder $container
     */
    private function registerJsonProvider(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Consumer\\JsonConsumer');
        $definition->addTag(static::JSON_PROVIDER_TAG);
        $container->setDefinition('json_spec.provider.json_provider.manual', $definition);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerContextInitializers(ContainerBuilder $container)
    {
        $definition = new Definition('JsonSpec\\Behat\\Context\\Initializer\\JsonConsumerAwareInitializer', array(
            new Reference('json_spec.provider.json_provider.manual'),
        ));
        $definition->addTag(ContextExtension::INITIALIZER_TAG);
        $container->setDefinition('json_spec.json_consumer_aware_initializer', $definition);

        $definition = new Definition('JsonSpec\\Behat\\Context\\Initializer\\JsonSpecContextInitializer', array(
            new Reference('json_spec.json_provider_voter'),
            new Reference('json_spec.matcher'),
            new Reference('json_spec.helper.json_helper'),
            new Reference('json_spec.helper.file_helper'),
            new Reference('json_spec.helper.memory_helper'),
        ));
        $definition->addTag(ContextExtension::INITIALIZER_TAG);
        $container->setDefinition('json_spec.context_initializer', $definition);
    }

    private function provideWebApiIntegration(ContainerBuilder $container)
    {
        if (!$this->webApiExtensionClassname) {
            echo "No web api for integrate";
            return;
        }

        $webApiExtensionReflection = new \ReflectionClass($this->webApiExtensionClassname);
        if (!$webApiExtensionReflection->hasConstant('CLIENT_ID')) {
            echo "web api extension doesnt have CLIENT_ID constant";
            return;
        }

        $definitionName = $webApiExtensionReflection->getConstant('CLIENT_ID');

        if (!$container->hasDefinition($definitionName)) {
            // json_spec should be initialized after web_api
            return;
        }

        // rename definition
        $originalDefinition = $container->getDefinition($definitionName);
        $originalDefinitionName = $definitionName.'.original';
        $container->setDefinition($originalDefinitionName, $originalDefinition);

        // provide wrapped Guzzle/Client
        $wrappedClientDefinition = new Definition(
            'JsonSpec\\Behat\\Wrapper\\GuzzleClient', array(
                new Reference($originalDefinitionName)
            )
        );
        $container->setDefinition($definitionName, $wrappedClientDefinition);
        $webApiJsonProviderDefinition = new Definition(
            'JsonSpec\\Behat\Provider\WebApiProvider',
            array(
                new Reference($definitionName)
            )
        );
        $webApiJsonProviderDefinition->addTag(static::JSON_PROVIDER_TAG);
        $container->setDefinition('json_spec.provider.json_provider.web_api', $webApiJsonProviderDefinition);
    }

    private function registerJsonProviderVoter(ContainerBuilder $container)
    {
        /** @var Definition[] $taggedServices */
        $taggedServiceIds = $container->findTaggedServiceIds(static::JSON_PROVIDER_TAG);
        $definition = new Definition('JsonSpec\\Behat\\Provider\\JsonProviderVoter');
        foreach ($taggedServiceIds as $id=>$attributes) {
            $definition->addMethodCall('addProvider', array(
                new Reference($id)
            ));
        }

        $container->setDefinition('json_spec.json_provider_voter', $definition);
    }

}
