<?php

namespace Ano\Bundle\TwigExtBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use DateTime;

/**
 *
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class TextExtension extends \Twig_Extension
{
    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'strr' => new \Twig_Filter_Method($this, 'stringReplace', array('needs_environment' => true)),
        );
    }

    public function stringReplace(\Twig_Environment $env, $string, $search, $replace, $ci = false)
    {
        if ($ci) {
            return str_ireplace($search, $replace, $string);
        }

        return str_replace($search, $replace, $string);
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'ano_text';
    }
}
