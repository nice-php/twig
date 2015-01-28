<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Nice\Extension;

use Nice\DependencyInjection\Compiler\CacheTwigTemplatesPass;
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
     * @var array
     */
    private $options = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Returns extension configuration
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @return TwigConfiguration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new TwigConfiguration();
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
        $configs[] = $this->options;
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('twig.template_dir', $config['template_dir']);

        $container->register('twig.asset_extension', 'Nice\Twig\AssetExtension')
            ->addArgument(new Reference('service_container'))
            ->addTag('twig.extension');

        $container->register('twig.router_extension', 'Nice\Twig\RouterExtension')
            ->addArgument(new Reference('service_container'))
            ->addTag('twig.extension');

        $container->register('twig.loader', 'Twig_Loader_Filesystem')
            ->addArgument(array('%twig.template_dir%'));

        $container->register('twig', 'Twig_Environment')
            ->addArgument(new Reference('twig.loader'));

        $container->register('templating.engine.twig', 'Nice\Templating\TwigEngine')
            ->addArgument(new Reference('twig'))
            ->addArgument(new Reference('templating.template_name_parser'))
            ->addTag('templating.engine');
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
            new CacheTwigTemplatesPass()
        );
    }
}
