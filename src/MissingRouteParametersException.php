<?php
/**
 * Part of the Route Generator Plugin
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 * @license MIT
 */
namespace Route\Generator;

use InvalidArgumentException;

/**
 * Exception for missing route parameters
 *
 * @package Route\Generator\RouteGenerator
 * @author  Nigel Greenway <nigel_greenway@me.com>
 */
class MissingRouteParametersException extends InvalidArgumentException
{
    /**
     * Class constructor
     *
     * @param string $alias
     * @param array $missingParameters
     */
    public function __construct($alias, array $missingParameters = [])
    {
        return new parent(
            sprintf(
                'The route alias [%s] requires the params `%s`',
                $alias,
                implode(',',$missingParameters)
            )
        );
    }
}