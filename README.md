# route-generator-plugin

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

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
