<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Nice\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterTwigExtensionsPass implements CompilerPassInterface
{
    /**
     * Registers services tagged with "twig.extension" with Twig
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig')) {
            return;
        }

        $definition = $container->getDefinition('twig');
        foreach ($container->findTaggedServiceIds('twig.extension') as $service => $tag) {
            $definition->addMethodCall('addExtension', array(new Reference($service)));
        }
    }
}
