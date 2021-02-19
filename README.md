<p align="center">
    <a href="https://packagist.org/packages/devlop/speedtrap"><img src="https://img.shields.io/packagist/v/devlop/speedtrap" alt="Latest Stable Version"></a>
    <a href="https://github.com/devlop-ab/speedtrap/blob/master/LICENSE.md"><img src="https://img.shields.io/packagist/l/devlop/speedtrap" alt="License"></a>
</p>

# Speedtrap

Simple speedtrap honeypot made for Laravel FormRequest that detects spam bots by measuring the time it took to submit the form.

# Installation

```
composer require devlop/speedtrap
```

If you wish to change any of the speedtrap configuration (such as the default threshold of 5 seconds) you can publish the config, but this is usually not needed.

```
php artisan vendor:publish --provider="Devlop\Speedtrap\SpeedtrapServiceProvider"
```

# Usage

First, add the `WithSpeedtrap` trait to your FormRequest.

```php
namespace App\Http\Requests;

use Devlop\Speedtrap\WithSpeedtrap;
use Illuminate\Foundation\Http\FormRequest;

class DemoRequest extends FormRequest
{
    use WithSpeedtrap;

```

Next, you need to configure the validation, it can either be automatic or manual.

## Automatic validation

Add the speedtrap rules to your rules configuration, this will make it redirect back to the form, as any other form validation error.

```php
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return $this->withSpeedtrapRules([
            // your normal rules goes here
        ]);
    }
```

Optionally you can also register the rules like this

```php
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            // your normal rules goes here,
            $this->getSpeedtrapInputName() => $this->speedtrapRules(),
        ];
    }
```

## Manual validation

If you are doing the validation manually you have more control of how you handle spammers,
maybe you want to silently ignore it and give the spammer the impression of success? it's all up to you.

```php
namespace App\Http\Controllers;

use App\Requests\DemoRequest;
use Illuminate\Http\Request;

class DemoController
{
    public function store(DemoRequest $request)
    {
        // get the speedtrap
        $speedtrap = $request->speedtrap();

        if ($speedtrap->triggered()) {
            // do something when the speedtrap was triggered
        }
    }
}
```
