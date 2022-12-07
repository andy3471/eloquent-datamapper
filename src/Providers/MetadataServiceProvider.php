<?php

namespace ProAI\Datamapper\Providers;

use Illuminate\Support\ServiceProvider;
use ProAI\Datamapper\Metadata\ClassFinder;
use ProAI\Datamapper\Metadata\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use \HaydenPierce\ClassFinder\ClassFinder as FilesystemClassFinder;

class MetadataServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAnnotations();

        $this->registerAnnotationReader();

        $this->registerClassFinder();
    }

    /**
     * Registers all annotation classes
     *
     * @return void
     */
    public function registerAnnotations()
    {
        $app = $this->app;

        $loader = new AnnotationLoader($app['files'], __DIR__ . '/../Annotations');

        $loader->registerAll();
    }

    /**
     * Register the class finder implementation.
     *
     * @return void
     */
    protected function registerAnnotationReader()
    {
        $this->app->singleton('datamapper.annotationreader', function ($app) {
            return new AnnotationReader;
        });
    }

    /**
     * Register the class finder implementation.
     *
     * @return void
     */
    protected function registerClassFinder()
    {
        $this->app->singleton('datamapper.classfinder', function ($app) {
            $finder = new FilesystemClassFinder;

            return new ClassFinder($finder);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'datamapper.classfinder',
            'datamapper.annotationreader',
        ];
    }
}
