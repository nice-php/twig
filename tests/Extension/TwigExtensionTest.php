<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Nice\Tests\Extension;

use Nice\Extension\TwigExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the TwigExtension
     */
    public function testConfigure()
    {
        $extension = new TwigExtension(array(
            'template_dir' => '/path'
        ));

        $container = new ContainerBuilder();
        $extension->load(array(), $container);

        $this->assertEquals('/path', $container->getParameter('twig.template_dir'));
        $this->assertTrue($container->hasDefinition('twig'));
        $this->assertTrue($container->hasDefinition('templating.engine.twig'));
    }

    /**
     * Test the getCompilerPasses method
     */
    public function testGetCompilerPasses()
    {
        $extension = new TwigExtension();

        $passes = $extension->getCompilerPasses();

        $this->assertCount(2, $passes);
        $this->assertInstanceOf('Nice\DependencyInjection\Compiler\RegisterTwigExtensionsPass', $passes[0]);
        $this->assertInstanceOf('Nice\DependencyInjection\Compiler\CacheTwigTemplatesPass', $passes[1]);
    }
}
