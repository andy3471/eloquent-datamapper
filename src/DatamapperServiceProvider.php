<?php

namespace AndyH\Datamapper;

use AndyH\Datamapper\Providers\BaseServiceProvider;

class DatamapperServiceProvider extends BaseServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        parent::register();
    }

    /**
     * Register the config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $configPath = __DIR__.'/../config/datamapper.php';

        $this->mergeConfigFrom($configPath, 'datamapper');

        $this->publishes([$configPath => config_path('datamapper.php')], 'config');
    }
}
