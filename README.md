## Installation

#### Composer

There are multiple way to install package via composer

* To your `composer.json` file add following lines:

    ```json
    // to your "require" object
    "vi-kon/laravel-support": "^1.0"
    ```

* Or run following command in project root:

    ```bash
    composer require vi-kon/laravel-auth
    ```

    This command will add above line in your composer.json file and download
    required package files.

#### Setup

In your Laravel project add following lines to `config/app.php`:

```php
// to providers array
\ViKon\Support\SupportServiceProvider::class,
```

In `app\Http\Kernel.php` file add following lines to `$middleware` array:

```php
// to $middleware array
\Illuminate\View\Middleware\ShareErrorsFromSession::class,
// Add after \Illuminate\View\Middleware\ShareErrorsFromSession::class,
\ViKon\Support\Middleware\View\ShareSuccessesFromSession::class,
```

**Note**: In Laravel 5.2 add these lines instead of `$middleware` array
to array marked with `web` key inside `$middlewareGroups` array.

---
[Back to top][top]