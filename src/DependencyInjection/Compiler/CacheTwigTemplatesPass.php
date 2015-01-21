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

/**
 * Adds caching directives to Twig Environment
 */
class CacheTwigTemplatesPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig') || $container->getParameter('app.cache') === false) {
            return;
        }

        $pathPrefix = '%app.cache_dir%/twig/';

        $definition = $container->getDefinition('twig');
        $definition->addMethodCall('setCache', array($pathPrefix . 'tpl'));
    }
}
