<?php

namespace Ano\Bundle\TwigExtBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class UrlExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'add_url' => new \Twig_Function_Method($this, 'addUrl'),
        );
    }

    public function addUrl($param, $value)
    {
        $request = $this->getRequest();
        $currentUrl = $request->getRequestUri();
        $queryString = $request->getQueryString();

        $count = 0;
        $currentUrl = preg_replace('@(' . $param . '=\S+)@i', urlencode($param) . '=' . urlencode($value), $currentUrl, -1, $count);
        if ($count <= 0) {
            $urlPattern = (empty($queryString)) ? '%s?%s=%s' : '%s&%s=%s';

            return sprintf($urlPattern, $currentUrl, urlencode($param), urlencode($value));
        }

        return $currentUrl;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'url';
    }
}
