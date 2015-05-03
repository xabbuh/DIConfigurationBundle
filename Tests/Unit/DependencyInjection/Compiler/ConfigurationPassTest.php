<?php

/*
 * This file is part of the XabbuhDIConfigurationBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\DIConfigurationBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Xabbuh\DIConfigurationBundle\DependencyInjection\Compiler\ConfigurationPass;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationPassTest extends AbstractCompilerPassTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->registerServiceDefinitions();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage A service with id "app.logger" is not registered.
     */
    public function testForNonExistingServiceThrowsRuntimeException()
    {
        $this->configureServices(array('app.logger' => array('class' => 'Logger')));
        $this->compile();
    }

    public function testCanChangeClassName()
    {
        $this->configureServices(array(
            'monolog.logger' => array('class' => 'CustomLogger'),
        ));
        $this->compile();

        $this->assertContainerBuilderHasService('monolog.logger', 'CustomLogger');
    }

    public function testCanChangeScalarArguments()
    {
        $this->configureServices(array(
            'locale_listener' => array('arguments' => array(
                array('index' => 0, 'value' => 'de'),
            )),
        ));
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('locale_listener', 0, 'de');
    }

    public function testCanChangeServiceReferenceArguments()
    {
        $this->configureServices(array(
            'locale_listener' => array('arguments' => array(
                array('index' => 1, 'value' => '@app.router'),
            )),
        ));
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('locale_listener', 1, new Reference('app.router'));
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationPass());
    }

    private function registerServiceDefinitions()
    {
        $this->container->register('monolog.logger', 'Monolog\Logger');

        $localeListener = new Definition('Symfony\Component\HttpKernel\EventListener\LocaleListener');
        $localeListener->addArgument('%kernel.default_locale%');
        $localeListener->addArgument(new Reference('router', ContainerInterface::IGNORE_ON_INVALID_REFERENCE));
        $localeListener->addArgument(new Reference('request_stack'));
        $this->container->setDefinition('locale_listener', $localeListener);
    }

    private function configureServices($configuration)
    {
        $this->container->setParameter('xabbuh_di_configuration.services', $configuration);
    }
}
