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
            'url_push' => new \Twig_Function_Method($this, 'urlPush'),
            'url_pull' => new \Twig_Function_Method($this, 'urlPull'),
        );
    }

    public function urlPush($name, $value, $url = null)
    {
        $urlParts = $this->getCurrentUrlParts($url);
        $urlParts['params'][$name] = $value;

        return $this->buildUrl($urlParts);
    }

    public function urlPull($param, $url = null)
    {
        $urlParts = $this->getCurrentUrlParts($url);
        if (is_array($param)) {
            foreach($param as $name) {
                if (array_key_exists($name, $urlParts['params'])) {
                    unset($urlParts['params'][$name]);
                }
            }
        }
        else {
            if (array_key_exists($param, $urlParts['params'])) {
                unset($urlParts['params'][$param]);
            }
        }

        return $this->buildUrl($urlParts);
    }

    protected function getCurrentUrlParts($url = null)
    {
        $useCurrent = false;
        $request = $this->getRequest();
        if (empty($url)) {
            $useCurrent = true;
            $url = $request->getRequestUri();
        }
        $urlParts = parse_url($url);

        if ($useCurrent) {
            $urlParts['scheme'] = $request->getScheme();
            $urlParts['host'] = $request->getHttpHost();
            $urlParts['baseUrl'] = $request->getBaseUrl();
            $urlParts['path'] = $request->getPathInfo();
            $urlParts['port'] = $request->getPort();
        }

        $urlParts['params'] = array();
        if (array_key_exists('query', $urlParts)) {
            parse_str($urlParts['query'], $urlParts['params']);
        }

        return $urlParts;
    }

    protected function buildUrl(array $urlParts)
    {
        $scheme = array_key_exists('scheme', $urlParts) ? $urlParts['scheme'] : '';
        $host = array_key_exists('host', $urlParts) ? $urlParts['host'] : '';
        $path = array_key_exists('path', $urlParts) ? $urlParts['path'] : '';
        $baseUrl = array_key_exists('baseUrl', $urlParts) ? $urlParts['baseUrl'] : '';
        $port = array_key_exists('port', $urlParts) ? $urlParts['port'] : 80;
        $qs = http_build_query($urlParts['params']);

        if ('http' === $scheme && 80 != $port) {
            $port = ':' . $port;
        } elseif ('https' === $scheme && 443 != $port) {
            $port = ':' . $port;
        }
        if (80 == $port || 443 == $port) {
            $port = '';
        }

        $url = (!empty($baseUrl)) ? '/' . $baseUrl : '';
        $url.= $path;
        $url.= (!empty($qs)) ? '?' . $qs : '';
        $prefix = (!empty($scheme)) ? $scheme . '://' : '';
        $url = $prefix . $host . $port . $url;
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
