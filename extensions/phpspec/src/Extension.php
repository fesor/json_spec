<?php

namespace JsonSpec\PhpSpec;

use JsonSpec\Helper\FileHelper;
use JsonSpec\JsonSpecMatcher;
use Seld\JsonLint\JsonParser;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\PhpSpec\Runner\Maintainer\DelayedMatcherMaintainer;
use JsonSpec\PhpSpec\Runner\Maintainer\JsonSpecMaintainer;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use JsonSpec\MatcherOptionsFactory;

class Extension implements ExtensionInterface
{

    /**
     * Register dependencies
     *
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $this->configuration($container);
        $this->registerInternalServices($container);

        $container->setShared('runner.maintainers.json_spec_maintainer', function (ServiceContainer $c) {
            return new JsonSpecMaintainer(
                $c->get('json_spec.matcher'),
                $c->get('json_spec.file_helper')
            );
        });

    }

    private function configuration(ServiceContainer $container)
    {
        $container->addConfigurator(function (ServiceContainer $c) {

            $json_spec = $c->getParam('json_spec');
            $excludedKeys = array('id');
            if (isset($json_spec['excluded_keys']) && is_array($json_spec['excluded_keys'])) {
                $excludedKeys = $json_spec['excluded_keys'];
            }
            $c->setParam('json_spec.excluded_keys', $excludedKeys);

            $jsonDirectory = isset($json_spec['json_directory']) ?
                $json_spec['json_directory'] : null;
            $c->setParam('json_spec.json_directory', $jsonDirectory);
        });
    }

    private function registerInternalServices(ServiceContainer $container)
    {
        $container->setShared('json_spec.json_lint', function () {
            return new JsonParser();
        });

        $container->setShared('json_spec.file_helper', function (ServiceContainer $container) {
            return new FileHelper($container->getParam('json_spec.json_directory'));
        });

        $container->setShared('json_spec.helper.json', function (ServiceContainer $container) {
            return new JsonHelper($container->get('json_spec.json_lint'));
        });

        $container->setShared('json_spec.matcher', function (ServiceContainer $container) {
            return new JsonSpecMatcher($container->get('json_spec.helper.json'), $container->getParam('json_spec.excluded_keys'));
        });
    }

}
