<?php

namespace Ano\Bundle\TwigExtBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use DateTime;

/**
 *
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class AgoExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->container->get('translator');
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'ago' => new \Twig_Function_Method($this, 'getAgo'),            
        );
    }

    public function getAgo(DateTime $date)
    {        
        $interval = date_create('now')->diff($date);
        //$suffix = ( $interval->invert ? ' ago' : '' );
        if ($interval->y >= 1) {
            return $this->pluralize($interval->y, 'year');
        }
        if ($interval->m >= 1) {
            return $this->pluralize($interval->m, 'month');
        }
        if ($interval->d >= 1) {
            return $this->pluralize($interval->d, 'day');
        }
        if ($interval->h >= 1) {
            return $this->pluralize($interval->h, 'hour');
        }
        if ($interval->i >= 1) {
            return $this->pluralize($interval->i, 'minute');
        }
        
        return $this->pluralize($interval->s, 'second');
    }
    
    public function pluralize( $count, $text ) 
    {
        $timeunit = ($count > 1 && substr($text, -1) != 's') ? $text . 's' : $text;
        $tr = $this->getTranslator();
        
        return $tr->trans('ago', array('%count%' => $count, '%timeunit%' => $tr->trans($timeunit, array(), 'AnoTwigExtBundle')), 'AnoTwigExtBundle');
    }
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ago';
    }
}
