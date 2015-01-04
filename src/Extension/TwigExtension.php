<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Nice\Extension;

use Nice\DependencyInjection\Compiler\CacheDataPass;
use Nice\DependencyInjection\Compiler\RegisterTwigExtensionsPass;
use Nice\DependencyInjection\CompilerAwareExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Sets up Twig services
 */
class TwigExtension extends Extension implements CompilerAwareExtensionInterface
{
    /**
     * @var string
     */
    protected $templateDir;

    /**
     * Constructor
     *
     * @param string $templateDir
     */
    public function __construct($templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $container->register('twig.asset_extension', 'Nice\Twig\AssetExtension')
            ->setPublic(false)
            ->addArgument(new Reference('service_container'))
            ->addTag('twig.extension');

        $container->register('twig.router_extension', 'Nice\Twig\RouterExtension')
            ->setPublic(false)
            ->addArgument(new Reference('service_container'))
            ->addTag('twig.extension');

        $container->setParameter('twig.template_dir', $this->templateDir);
        $container->register('twig.loader', 'Twig_Loader_Filesystem')
            ->addArgument(array('%twig.template_dir%'));

        $container->register('twig', 'Twig_Environment')
            ->addArgument(new Reference('twig.loader'));
    }

    /**
     * Gets the CompilerPasses this extension requires.
     *
     * @return array|CompilerPassInterface[]
     */
    public function getCompilerPasses()
    {
        return array(
            new RegisterTwigExtensionsPass(),
            new CacheDataPass()
        );
    }
}
