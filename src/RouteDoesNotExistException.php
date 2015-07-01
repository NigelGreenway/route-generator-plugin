<?php
/**
 * Part of the Route Generator Plugin
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 * @license MIT
 */
namespace Route\Generator;

use Exception;

/**
 * Exception for non existent route
 *
 * @package Route\Generator\RouteGenerator
 * @author  Nigel Greenway <nigel_greenway@me.com>
 */
final class RouteDoesNotExistException extends Exception
{
    /**
     * Class constructor
     *
     * @param string $alias
     */
    public function __construct($alias)
    {
        return new parent(
            sprintf('The route [%s] does not exist. Please check your routes file.', $alias)
        );
    }
}