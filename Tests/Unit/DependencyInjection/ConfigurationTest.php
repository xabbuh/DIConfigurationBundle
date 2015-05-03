<?php

/*
 * This file is part of the XabbuhDIConfigurationBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\DIConfigurationBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use Symfony\Component\Config\Definition\Processor;
use Xabbuh\DIConfigurationBundle\DependencyInjection\Configuration;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationTest extends AbstractConfigurationTestCase
{
    public function testScalarValueIsTreatedAsClassName()
    {
        $this->assertProcessedConfigurationEquals(
            array(array('monolog.logger' => 'CustomLogger')),
            array('monolog.logger' => array(
                'class' => 'CustomLogger',
                'arguments' => array(),
            ))
        );
    }

    public function testArgumentsWithoutIndexesStartWithZero()
    {
        $this->assertProcessedConfigurationEquals(
            array(array('locale_listener' => array('arguments' => array(
                'de',
                '@app.router',
            )))),
            array('locale_listener' => array('arguments' => array(
                array(
                    'index' => 0,
                    'value' => 'de',
                ),
                array(
                    'index' => 1,
                    'value' => '@app.router',
                ),
            )))
        );
    }

    public function testIndexCanBeUsedToSkipArguments()
    {
        $this->assertProcessedConfigurationEquals(
            array(array('locale_listener' => array('arguments' => array(
                'de',
                array('index' => 2, 'value' => '@app.request_stack'),
            )))),
            array('locale_listener' => array('arguments' => array(
                array(
                    'index' => 0,
                    'value' => 'de',
                ),
                array(
                    'index' => 2,
                    'value' => '@app.request_stack',
                ),
            )))
        );
    }

    public function testFullConfiguration()
    {
        $this->assertProcessedConfigurationEquals(
            array(array(
                'monolog.logger' => array('class' => 'CustomLogger'),
            )),
            array(
                'monolog.logger' => array(
                    'class' => 'CustomLogger',
                    'arguments' => array(),
                ),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
