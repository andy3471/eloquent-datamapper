<?php

namespace AndyH\Datamapper\Providers;

class LumenServiceProvider extends BaseServiceProvider
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
        $this->app->configure('datamapper');
    }
}
