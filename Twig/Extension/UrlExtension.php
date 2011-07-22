<?php

namespace Ano\Bundle\TwigExtBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

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
            'push_url' => new \Twig_Function_Method($this, 'pushUrl'),
            'pull_url' => new \Twig_Function_Method($this, 'pullUrl'),
        );
    }

    public function pushUrl($param, $value)
    {
        $urlParts = $this->getCurrentUrlParts();
        $urlParts['params'][$param] = $value;

        return $this->buildUrl($urlParts);
    }

    public function pullUrl($param)
    {
        $urlParts = $this->getCurrentUrlParts();
        if (array_key_exists($param, $urlParts['params'])) {
            unset($urlParts['params'][$param]);
        }

        return $this->buildUrl($urlParts);
    }

    protected function getCurrentUrlParts()
    {
        $request = $this->getRequest();
        $urlParts = parse_url($request->getRequestUri());
        $urlParts['scheme'] = $request->getScheme();
        $urlParts['host'] = $request->getHttpHost();
        $urlParts['baseUrl'] = $request->getBaseUrl();
        $urlParts['path'] = $request->getPathInfo();
        $urlParts['port'] = $request->getPort();
        $urlParts['params'] = array();
        parse_str($urlParts['query'], $urlParts['params']);

        return $urlParts;
    }

    protected function buildUrl(array $urlParts)
    {
        $request = $this->getRequest();
        $scheme = $urlParts['scheme'];
        $baseUrl = $urlParts['baseUrl'];
        $qs = http_build_query($urlParts['params']);
        
        $port = '';
        if ('http' === $scheme && 80 != $urlParts['port']) {
            $port = ':' . $urlParts['port'];
        } elseif ('https' === $scheme && 443 != $urlParts['port']) {
            $port = ':' . $urlParts['port'];
        }

        $url = (!empty($baseUrl)) ? '/' . $baseUrl : '';
        $url.= $urlParts['path'];
        $url.= (!empty($qs)) ? '?' . $qs : '';
        $url = $scheme . '://' . $request->getHost() . $port . $url;
        $url.= (array_key_exists('anchor', $urlParts)) ? '#' . $urlParts['anchor'] : '';

        return $url;
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
