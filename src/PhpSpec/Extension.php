<?php

namespace JsonSpec\PhpSpec;

use Seld\JsonLint\JsonParser;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\PhpSpec\Runner\Maintainer\DelayedMatcherMaintainer;
use JsonSpec\PhpSpec\Runner\Maintainer\JsonSpecMaintainer;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

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
                $c->get('json_spec.helper.json'),
                $c->get('json_spec.matcher_options_factory')
            );
        });

        $container->setShared('runner.maintainers.delayed_matcher_maintainer', function (ServiceContainer $c) {
            return new DelayedMatcherMaintainer(
                $c->get('formatter.presenter'),
                $c->get('unwrapper'),
                $c->get('event_dispatcher')
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
        });
    }

    private function registerInternalServices(ServiceContainer $container)
    {
        $container->setShared('json_spec.json_lint', function () {
            return new JsonParser();
        });

        $container->setShared('json_spec.matcher_options_factory', function (ServiceContainer $container) {
            return new MatcherOptionsFactory($container->getParam('json_spec.excluded_keys'));
        });

        $container->setShared('json_spec.helper.json', function (ServiceContainer $container) {
            return new JsonHelper($container->get('json_spec.json_lint'));
        });
    }

}
