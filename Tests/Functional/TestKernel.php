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

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Xabbuh\DIConfigurationBundle\XabbuhDIConfigurationBundle;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new XabbuhDIConfigurationBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }
}
