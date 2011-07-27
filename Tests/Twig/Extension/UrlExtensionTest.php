<?php

namespace Ano\Bundle\TwigExtBundle\Tests\Twig\Extension;
use Ano\Bundle\TwigExtBundle\Twig\Extension\UrlExtension;


class UrlExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $templates;

    public function setUp()
    {
        $this->templates = array(
            '1_push1' => '{{ url_push("p", 2) }}',
            '1_push2' => '{{ url_push("p", 2, "/test") }}',
            '1_push3' => '{{ url_push("p", 2, "http://www.test.tld/" )}}',
            '1_pull1' => '{{ url_pull("p") }}',
            '1_pull2' => '{{ url_pull("p", "/test") }}',
            '1_pull3' => '{{ url_pull("p", "/test?foo=bar&p=2&bar=baz") }}',
            '1_pull4' => '{{ url_pull("p", "http://www.test.tld/") }}',
            '1_pull5' => '{{ url_pull("p", "http://www.test.tld/?foo=bar&p=2&bar=baz") }}',
            '1_pull6' => '{{ url_pull(["p", "q"], "http://www.test.tld/?foo=bar&p=2&bar=baz&q=test") }}',
        );
    }

    public function testPushParamInFullUrl()
    {

        $this->assertTrue($menu instanceof Menu);
    }

    public function testCreateMenuWithAttributes()
    {
        $menu = new Menu(array('class' => 'root'));
        $this->assertEquals('root', $menu->getAttribute('class'));
    }

    public function testCreateMenuWithItemClass()
    {
        $childClass = 'Knp\Bundle\MenuBundle\OtherMenuItem';
        $menu = new Menu(null, $childClass);
        $this->assertEquals($childClass, $menu->getChildClass());
    }

    protected function getEnvironment($templates, $tags = array(), $filters = array(), $methods = array(), $properties = array(), $functions = array())
    {
        $loader = new Twig_Loader_Array($templates);
        $twig = new Twig_Environment($loader, array('debug' => true, 'cache' => false, 'autoescape' => false));
        $twig->addExtension(new UrlExtension());

        return $twig;
    }
}
