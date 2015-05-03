<?php

/*
 * This file is part of the XabbuhDIConfigurationBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\DIConfigurationBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class XabbuhDIConfigurationBundleTest extends WebTestCase
{
    public function testConfigureServices()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        /** @var \LocaleListener $localeListener */
        $localeListener = $container->get('locale_listener');
        $this->assertSame('de', $localeListener->defaultLocale);
        $this->assertInstanceOf('CustomRequestStack', $localeListener->requestStack);

        $monologLogger = $container->get('monolog.logger');
        $this->assertInstanceOf('CustomLogger', $monologLogger);
    }

    protected static function getKernelClass()
    {
        return 'Xabbuh\DIConfigurationBundle\Tests\Functional\TestKernel';
    }
}
