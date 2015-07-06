<?php
/**
 * ...
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 * @license ...
 */

namespace Route\Generator\RouteGeneratorTest\TemplateExtension;

use PHPUnit_Framework_TestCase;
use Route\Generator\RouteGenerator;
use Route\Generator\TemplateExtension\TwigRouteGeneratorExtension;
use Twig_Loader_Filesystem;
use Twig_Environment;

class TwigRouteGeneratorExtensionTest extends PHPUnit_Framework_TestCase
{
    /** @var array */
    private $routes;
    /** @var Twig_Environment */
    private $twig;

    public function setUp()
    {
        $_SERVER['HTTP_HOST'] = 'testallthethings.com';
        $this
            ->routes = [
                'exposed_route_a' => [
                    'pattern'    => '/users/search',
                    'controller' => function() {
                        echo 'This is a static route';
                    },
                    'method'     => ['GET'],
                    'expose'     => true,
                ],
                'module' => [
                    'static_route' => [
                        'pattern'    => '/users/search',
                        'controller' => function() {
                            echo 'This is a static route';
                        },
                        'method'     => ['GET'],
                    ],
                    'dynamic_route' => [
                        'pattern'    => '/users/{id}',
                        'controller' => function($id) {
                            echo 'Your user id is '.$id;
                        },
                        'method'     => ['GET'],
                    ],
                    'exposed_route_b' => [
                        'pattern'    => '/users/search',
                        'controller' => function() {
                            echo 'This is a static route';
                        },
                        'method'     => ['GET'],
                        'expose'     => true,
                    ],
                ],
            ];

        $templateGenerator = new RouteGenerator($this->routes);

        $loader = new Twig_Loader_Filesystem();

        $loader->addPath(__DIR__ . '/../Fixture/Twig', 'Fixture');

        $this
            ->twig = new Twig_Environment($loader);
        $this
            ->twig
            ->addExtension(new TwigRouteGeneratorExtension($templateGenerator));
    }

    /**
     * @expectedException Twig_Error_Runtime
     */
    public function test_a_non_existent_route_throws_does_not_exist_exception()
    {
        $this
            ->twig
            ->render('@Fixture/non_existent_route.txt.twig');
    }

    /**
     * Route\Generator\TemplateExtension\TwigRouteGeneratorExtension::generate
     */
    public function test_a_static_relative_route_is_generated()
    {
        $template = $this
            ->twig
            ->render('@Fixture/static_relative_route.txt.twig');

        $this->assertContains('/users/search', $template);
    }

    /**
     * Route\Generator\TemplateExtension\TwigRouteGeneratorExtension::generate
     */
    public function test_a_dynamic_relative_route_is_generated()
    {
        $template = $this
            ->twig
            ->render(
                '@Fixture/dynamic_relative_route.txt.twig',
                ['id' => 1]
            );

        $this->assertContains('/users/1', $template);
    }

    /**
     * @expectedException Twig_Error_Runtime
     */
    public function test_a_dynamic_relative_route_throws_missing_parameter_exception_with_no_parameters_passed()
    {
        $this
            ->twig
            ->render(
                '@Fixture/invalid_parameters_passed.txt.twig'
            );
    }

    /**
     * @expectedException Twig_Error_Runtime
     */
    public function test_a_dynamic_relative_route_throws_missing_parameter_exception_with_incorrect_parameters_passed()
    {
        $this
            ->twig
            ->render(
                '@Fixture/invalid_parameters_passed.txt.twig',
                [
                    'name' => 'Joe 90',
                ]
            );
    }

    /**
     * Route\Generator\TemplateExtension\TwigRouteGeneratorExtension::generate
     */
    public function test_a_static_absolute_route_is_generated()
    {
        $template = $this
            ->twig
            ->render('@Fixture/static_absolute_route.txt.twig');

        $this->assertContains('http://testallthethings.com/users/search', $template);
    }

    /**
     * Route\Generator\TemplateExtension\TwigRouteGeneratorExtension::generate
     */
    public function test_a_dynamic_absolute_route_is_generated()
    {
        $template = $this
            ->twig
            ->render(
                '@Fixture/dynamic_absolute_route.txt.twig',
                [
                    'id' => 1,
                ]
            );

        $this->assertContains('http://testallthethings.com/users/1', $template);
    }

    /**
     * Route\Generator\TemplateExtension\TwigRouteGeneratorExtension::getRoutes
     */
    public function test_all_exposed_routes_are_in_json_array()
    {
        $template = json_decode(
            $this
                ->twig
                ->render('@Fixture/fetch_routes.txt.twig'),
            true
        );

        $this->assertTrue(array_key_exists('exposed_route_a', $template));
        $this->assertTrue(array_key_exists('exposed_route_b', $template));
        $this->assertCount(2, $template);
    }

    /**
     * Route\Generator\TemplateExtension\TwigRouteGeneratorExtension::getRoutes
     */
    public function test_non_exposed_routes_are_not_in_json_array()
    {
        $template = json_decode(
            $this
                ->twig
                ->render('@Fixture/fetch_routes.txt.twig'),
            true
        );

        $this->assertFalse(array_key_exists('static_route', $template));
        $this->assertFalse(array_key_exists('dynamic_route', $template));
    }

    public function tearDown()
    {
        $this->twig
            = $this->routes
            = $_SERVER = null;
    }
}
