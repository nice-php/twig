<?php

namespace Nice\Templating;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\StreamingEngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TwigEngine implements EngineInterface, StreamingEngineInterface
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var TemplateNameParserInterface
     */
    private $parser;

    /**
     * Constructor
     *
     * @param \Twig_Environment           $environment A \Twig_Environment instance
     * @param TemplateNameParserInterface $parser      A TemplateNameParserInterface instance
     */
    public function __construct(\Twig_Environment $environment, TemplateNameParserInterface $parser)
    {
        $this->environment = $environment;
        $this->parser = $parser;
    }

    /**
     * @param string|TemplateReferenceInterface $name
     * @param array                             $parameters
     *
     * @return string
     */
    public function render($name, array $parameters = array())
    {
        return $this->load($name)->render($parameters);
    }

    /**
     * @param string|TemplateReferenceInterface $name
     * @param array                             $parameters
     */
    public function stream($name, array $parameters = array())
    {
        $this->load($name)->display($parameters);
    }

    /**
     * @param string|TemplateReferenceInterface $name
     *
     * @return bool
     */
    public function exists($name)
    {
        $loader = $this->environment->getLoader();

        try {
            $loader->getSource((string) $name);
        } catch (\Twig_Error_Loader $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string|TemplateReferenceInterface $name
     *
     * @return bool
     */
    public function supports($name)
    {
        $template = $this->parser->parse($name);

        return 'twig' === $template->get('engine');
    }

    /**
     * @param string|TemplateReferenceInterface $name
     *
     * @return \Twig_TemplateInterface
     */
    private function load($name)
    {
        return $this->environment->loadTemplate((string) $name);
    }
}
