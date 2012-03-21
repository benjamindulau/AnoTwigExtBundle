<?php

namespace Ano\Bundle\TwigExtBundle\Twig\Extension;

/**
 *
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class CoreExtension extends \Twig_Extension
{

    public function getTests()
    {
        return array(
            'hash'  => new \Twig_Function_Method($this, 'isArray'),
        );
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isArray($value)
    {
        return is_array($value);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ano_core';
    }
}
