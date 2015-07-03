<?php
/**
 * Part of the Route Generator Plugin
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 * @license MIT
 */
namespace Route\Generator;

/**
 * Route Generator
 *
 * Generate a route from an existing route within the Leage\Route config file
 *
 * @package Route\Generator\RouteGenerator
 */
final class RouteGenerator
{
    /** const ABSOLUTE Return an absolute url */
    const ABSOLUTE = true;

    /** const RELATIVE Return a relative url */
    const RELATIVE = false;

    /** @var array */
    private $routes;

    /** @var string */
    public static $regex = '#\{\w+\}#';

    /**
     * Class constructor
     *
     * @param array $routes
     */
    public function __construct(
        array $routes
    ) {
        $this->routes = $routes;
    }

    /**
     * Generate a [relative|absolute] URL from a given alias with its parameters.
     *
     * @param string $alias
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @throws MissingRouteParametersException
     *
     * @return string
     */
    public function generate(
              $alias,
        array $parameters = [],
              $absolute   = self::RELATIVE
    ){
        $route = $this->findRoute($alias);

        $elements = array_map(function($element) use ($alias, $parameters) {
            foreach ($parameters as $key => $value) {
                if (preg_match('#\{'.$key.'\}#', $element, $matches)) {
                    if (empty($value)) {
                        throw new MissingRouteParametersException($alias);
                    }
                    return preg_replace('#\{'.$key.'\}#', urlencode($value), $matches[0]);
                }
            }
            return $element;
        }, explode('/', $route['pattern']));

        $route = implode('/', $elements);

        if (preg_match_all(self::$regex, $route, $matches) > 0) {
            $arguments = array_map(function($arguments) {
                return $arguments;
            }, $matches[0]);

            throw new MissingRouteParametersException($alias, $arguments);
        }

        if ($absolute === self::ABSOLUTE) {
            return sprintf(
                '%s://%s%s',
                isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http',
                $_SERVER['HTTP_HOST'],
                $route
            );
        }

        return $route;
    }

    /**
     * Search for the alias name in the config property
     *
     * @param $alias
     *
     * @throws RouteDoesNotExistException
     *
     * @return mixed
     */
    private function findRoute($alias)
    {
        // If we have a non modular configuration, check if the key exists
        if (array_key_exists($alias, $this->routes)) {
            return $this->routes[$alias];
        }

        // If we have a modular configuration, check if the key exists
        foreach($this->routes as $modules) {
            if (array_key_exists($alias, $modules)) {
                return $modules[$alias];
            }
        }

        throw new RouteDoesNotExistException($alias);
    }
}
