<?php

namespace ProAI\Datamapper\Metadata;

use HaydenPierce\ClassFinder\ClassFinder as FilesystemClassFinder;

class ClassFinder
{
    /**
     * The class finder instance.
     *
     * @var FilesystemClassFinder
     */
    protected $finder;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new metadata builder instance.
     *
     * @param \Illuminate\Filesystem\ClassFinder $finder
     * @param array $config
     * @return void
     */
    public function __construct(FilesystemClassFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Get all classes for a given namespace.
     *
     * @param string $namespace
     * @return array
     */
    public function getClassesFromNamespace($namespace = null)
    {
        $namespace = $namespace ?: $this->getAppNamespace();

//        $path = $this->convertNamespaceToPath($namespace);

        return $this->finder->getClassesInNamespace($namespace);
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getAppNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents(base_path().'/composer.json'), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath(app('path')) == realpath(base_path().'/'.$pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }
        
        throw new RuntimeException('Unable to detect application namespace.');
    }
}
