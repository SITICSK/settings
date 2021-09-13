# Settings

This package is for creating Setting Categories. Recommened with some of direct settings package.

### Implementation

### 1. Install the package 
```
composer require sitic/settings
```
### 2. Add in **bootstrap/app.php**

**Config Files**
```php
/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/
$app->configure('settings');
```
**Providers**
```php
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/
$app->register(\Sitic\Settings\SettingsServiceProvider::class);
```
### 3. Run migrations
```
php artisan migrate
```

### 4. Install default settings
```
php artisan sitic:settings
```
