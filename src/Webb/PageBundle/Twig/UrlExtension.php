<?php
/*
 * src/Webb/PageBundle/Twig/UrlExtension.php
 */

namespace Webb\PageBundle\Twig;
use Twig_Extension;
use Twig_Filter_Method;

class UrlExtension extends Twig_Extension
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            'entityurl' => new Twig_Filter_Method($this, 'entityurl'),
        );
    }

    public function entityurl($entity, $routeName)
    {
        $router         = $this->container->get('router');
        $generator      = $router->getGenerator();
        $collection     = $router->getRouteCollection();
        $route          = $collection->get($routeName);
        $compiledRoute  = $route->compile();
        $variables      = $compiledRoute->getVariables();
        $parameters     = array();

        foreach($variables as $var)
        {
            $getter = 'get'.ucfirst($var);
            $parameters[$var]=$entity->$getter();
        }

        return $generator->generate($routeName,$parameters);

    }

    public function getName() {
        return 'url_extension';
    }
}