# route-generator-plugin

A route generation plugin for the League/Route package.

## Install

Via Composer

``` bash
$ composer require league/route-generator-plugin
```

## Usage

``` php
$generator = new Route\Generator\RouteGenerator([
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
            }
        ],
    ],
]);
echo $generator->generate('hello_word');
// /hello/world

echo $generator->generate('hello_person', ['name' => 'Timmy Mallet']);
// /hello/Timmy+Mallet
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email nigel_greenway@me.com instead of using the issue tracker.

## Credits

- [Nigel Greenway][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/NigelGreenway
[link-contributors]: ../../contributors
