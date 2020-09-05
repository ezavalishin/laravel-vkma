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

Guard get or create user in your db by `vk_user_key`

Next you can use middleware `auth:vkma` and send request with header `Vk-Params` base64 encoded vk launch params

#### Example

Javascript
```javascript
let instance = axios.create({
  headers: {
    common: {        // can be common or any other method
      'Vk-Params': btoa(window.location.search.substring(1))
    }
  }
})
``` 

Laravel
```php
$user = Auth::user();
```


### Filling user from vk

Package provides Job `FillUser` which filling your db with data from vk

#### Prepare

You should implement `VKMAUserInterface` and use trait `VKMAUserable` also map vk fields

```php
use Illuminate\Foundation\Auth\User as Authenticatable;

use ezavalishin\VKMA\Contracts\VKMAUserInterface;
use ezavalishin\VKMA\Traits\VKMAUserable;

class User extends Authenticatable implements VKMAUserInterface
{
    use VKMAUserable;

    public function vkFieldsMap(): array
    {
        return [
            //db field => vk field name

            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'birth_date' => 'bdate',
            'city_id' => 'city',
            'country_id' => 'country'
        ];
    }
}
```


Now you can easily fill your user model just dispatch job

```php
dispatch(new \ezavalishin\VKMA\Jobs\FillUser($user));
```  

Or you can do it when user created, just add in your model:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use ezavalishin\VKMA\Contracts\VKMAUserInterface;
use ezavalishin\VKMA\Traits\VKMAUserable;

use ezavalishin\VKMA\Jobs\FillUser;

class User extends Authenticatable implements VKMAUserInterface
{
    use VKMAUserable;
    
    ...

    public static function booted()
    {
        self::created(static function(self $model) {
            dispatch(new FillUser($model));
        });
    }
}
```


#### Custom parsers

When job fetch field from vk you can easily change its format

Add in your model public method `parse{VkFieldName}`(camel case) and return value which will be stored in db

Example: 

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use ezavalishin\VKMA\Contracts\VKMAUserInterface;
use ezavalishin\VKMA\Traits\VKMAUserable;

class User extends Authenticatable implements VKMAUserInterface
{
    use VKMAUserable;
    
    ...

    public function parseCountry($value)
    {
        return $value['id'];
    }

    public function parseCity($value)
    {
        return $value['id'];
    }
}
```

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
