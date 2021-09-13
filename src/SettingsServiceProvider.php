<?php

namespace Sitic\Settings;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Sitic\Settings\Console\Commands\sitic\InstallSettings;
use Sitic\Settings\Http\Models\SettingItem;

class SettingsServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->mergeConfigFrom(__DIR__.'/config/settings.php', 'settings');
        $this->loadJsonTranslationsFrom(__DIR__.'/resources/lang');

        // Publish config files
        $this->publishes([
            __DIR__.'/config/settings.php' => config_path('settings.php')
        ]);

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands(
             InstallSettings::class
            );
        }

        // Cache all settings
        $settings = Cache::remember('site_settings', 600, function () {
            return SettingItem::select('name', 'type', 'value')->get()->toArray();
        });
        Config::set('site_settings', $settings);
    }

    public function register()
    {

    }
}
