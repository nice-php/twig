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

/**
 * Adds caching directives to Twig Environment
 */
class CacheDataPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('app.cache') !== false) {
            $pathPrefix = '%app.cache_dir%/%app.env%/twig/';

            $definition = $container->getDefinition('twig');
            $definition->addMethodCall('setCache', array($pathPrefix . 'tpl'));
        }
    }
}
