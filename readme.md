# VKMA

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require ezavalishin/vkma
```

Publish config

``` bash
$ php artisan vendor:publish --provider="ezavalishin\VKMA\VKMAServiceProvider"
```

## Usage

### Authentication

Package provides auth driver `vkma`

You can put it in your `config/auth.php`

```php
'guards' => [
    ...
    'vkma' => [
        'driver' => 'vkma'
    ]
], 
```

Next you can use middleware `auth:vkma`


Guard get or create user in your db by `vk_user_key`

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email ezavalishin@gmail.com instead of using the issue tracker.

## Credits

- [Evgeniy Zavalishin][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.
