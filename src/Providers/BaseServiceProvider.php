<?php

namespace AndyH\Datamapper\Providers;

use Illuminate\Support\ServiceProvider;
use AndyH\Datamapper\EntityManager;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app['config']['datamapper.auto_scan']) {
            $this->scanEntities();
        }

        $this->registerEloquentModels();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEntityManager();

        $this->registerHelpers();

        $this->app->register('AndyH\Datamapper\Providers\CommandsServiceProvider');
    }

    /**
     * Register the entity manager implementation.
     *
     * @return void
     */
    protected function registerEntityManager()
    {
        $app = $this->app;

        $app->singleton('datamapper.entitymanager', function ($app) {
            return new EntityManager;
        });
    }

    /**
     * Register helpers.
     *
     * @return void
     */
    protected function registerHelpers()
    {
        require_once __DIR__.'/../Support/helpers.php';
    }

    /**
     * Scan entity annotations and update database.
     *
     * @return void
     */
    protected function scanEntities()
    {
        $app = $this->app;

        // get classes
        $classes = $app['datamapper.classfinder']->getClassesFromNamespace($app['config']['datamapper.models_namespace']);

        // build metadata
        $metadata = $app['datamapper.entity.scanner']->scan($classes, $app['config']['datamapper.namespace_tablenames'], $app['config']['datamapper.morphclass_abbreviations']);

        // generate eloquent models
        $app['datamapper.eloquent.generator']->generate($metadata, false);

        // build schema
        $app['datamapper.schema.builder']->update($metadata, false);
    }

    /**
     * Load the compiled eloquent entity models.
     *
     * @return void
     */
    protected function registerEloquentModels()
    {
        $files = $this->app['files']->files(storage_path('framework/entities'));

        foreach ($files as $file) {
            if ($this->app['files']->extension($file) == '') {
                require_once $file;
            }
        }
    }
}
