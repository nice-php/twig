<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Nice\Tests\Extension;

use Nice\DependencyInjection\Compiler\RegisterTwigExtensionsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterTwigExtensionsPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the RegisterTwigExtensionsPass
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $definition = $container->register('twig', 'Twig_Environment');

        $container->register('fake.extension', 'FakeExtension')
            ->addTag('twig.extension');

        $pass = new RegisterTwigExtensionsPass();

        $pass->process($container);

        $methodCalls = $definition->getMethodCalls();
        $this->assertCount(1, $methodCalls);

        $methodCall = $methodCalls[0];
        $this->assertEquals('addExtension', $methodCall[0]);
        $this->assertEquals(new Reference('fake.extension'), $methodCall[1][0]);
    }

    /**
     * Tests silent failure when Twig is not registered
     */
    public function testSilentFailWhenTwigServiceDoesntExist()
    {
        $container = new ContainerBuilder();

        $container->register('fake.extension', 'FakeExtension')
            ->addTag('twig.extension');

        $pass = new RegisterTwigExtensionsPass();

        $pass->process($container);

        // nothing happens
    }
}
