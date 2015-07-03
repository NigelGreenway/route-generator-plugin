<?php
/**
 * Part of the Route Generator Plugin
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 * @license MIT
 */
namespace Route\Generator\TemplateExtension;

use Route\Generator\RouteGenerator;
use Twig_Extension;

/**
 * Extension for twig to enable generating routes in a twig
 * template file
 *
 * @package Route\Generator\TemplateExtension
 * @author  Nigel Greenway
 */
class TwigRouteGeneratorExtension extends Twig_Extension
{
    /** @var RouteGenerator */
    private $generator;

    /**
     * Class constructor
     *
     * @param RouteGenerator $routeGenerator
     */
    public function __construct(
        RouteGenerator $routeGenerator
    ) {
        $this->generator = $routeGenerator;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('route', [$this, 'generate']),
        ];
    }

    /**
     * Generate a route via the RouteGenerator class
     *
     * @param $alias
     * @param array $parameters
     * @param bool $absolute
     *
     * @return string
     */
    public function generate(
              $alias,
        array $parameters = [],
              $absolute   = RouteGenerator::RELATIVE
    ) {
        return $this
            ->generator
            ->generate($alias, $parameters, $absolute);
    }

    /**
     * Required by Twig_ExtensionInterface
     *
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::class;
    }
}
