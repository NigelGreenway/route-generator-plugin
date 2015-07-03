<?php
/**
 * Part of the Route Generator Plugin
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 * @license MIT
 */
namespace Route\Generator\RouteGeneratorTest;

use League\Container\Container;
use League\Route\RouteCollection;
use League\Route\Strategy\UriStrategy;
use PHPUnit_Framework_TestCase;
use Route\Generator\RouteGenerator;

class RouteGeneratorTest extends PHPUnit_Framework_TestCase
{
    private $config,
            $router
    ;

    public function setUp()
    {
        $this->config = [
            'hello_module' => [
                'hello_world' => [
                    'pattern'    => '/hello/world',
                    'controller' => function() {
                        echo 'Hello world.';
                    },
                    'method'     => ['GET'],
                ],
                'hello_person' => [
                    'pattern'    => '/hello/{name}',
                    'controller' => function($name) {
                        echo 'Hello '.$name;
                    },
                    'method'     => ['GET'],
                ],
            ],
        ];

        $this->router = new RouteCollection(new Container([]));
        $this->router->setStrategy(new UriStrategy);

        foreach ($this->config as $collection) {
            foreach ($collection as $route) {
                $this->router->addRoute(
                    $route['method'],
                    $route['pattern'],
                    $route['controller']
                );
            }
        }
    }

    /**
     * @covers Route\Generator\RouteGenerator::__construct
     */
    public function test_route_generator_instantiates()
    {
        $generator = new RouteGenerator($this->config);

        $this->assertInstanceOf(RouteGenerator::class, $generator);
    }

    /**
     * @expectedException Route\Generator\RouteDoesNotExistException
     */
    public function test_no_route_found_exception_is_thrown()
    {
        $generator = new RouteGenerator($this->config);

        $generator->generate('invalid_route');
    }

    /**
     * @covers Route\Generator\RouteGenerator::__construct
     * @covers Route\Generator\RouteGenerator::generate
     */
    public function test_static_route_has_been_generated_correctly()
    {
        $generator = new RouteGenerator($this->config);

        $route = $generator->generate('hello_world');

        $this->assertEquals('/hello/world', $route);
    }

    /**
     * @expectedException Route\Generator\MissingRouteParametersException
     */
    public function test_dynamic_route_with_insufficient_parameters()
    {
        $generator = new RouteGenerator($this->config);

        $generator->generate('hello_person');
    }

    /**
     * @expectedException Route\Generator\MissingRouteParametersException
     */
    public function test_dynamic_route_with_incorrect_parameters()
    {
        $generator = new RouteGenerator($this->config);

        $generator->generate('hello_person', ['id' => 1]);
    }

    public function test_dynamic_route_with_correct_parameters()
    {
        $generator = new RouteGenerator($this->config);

        $route = $generator->generate('hello_person', ['name' => 'Timmy Mallet']);

        $this->assertEquals('/hello/Timmy+Mallet', $route);
    }

    /**
     * @covers Route\Generator\RouteGenerator::generate
     * @covers Route\Generator\RouteGenerator::findRoute
     */
    public function test_route_is_found_without_existing_in_a_module()
    {
        $generator = new RouteGenerator($this->config['hello_module']);

        $route = $generator->generate('hello_world');

        $this->assertEquals('/hello/world', $route);
    }

    public function tearDown()
    {
        $this->config
            = $this->router
            = null;
    }
}
