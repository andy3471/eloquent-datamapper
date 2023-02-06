<?php

namespace AndyH\Datamapper\Eloquent;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use AndyH\Datamapper\Support\Collection as DatamapperCollection;

class Collection extends EloquentCollection
{
    /**
     * Convert models to entity objects.
     *
     * @return \AndyH\Datamapper\Support\Collection
     */
    public function toDatamapperObject()
    {
        $entities = new DatamapperCollection;

        foreach ($this->items as $name => $item) {
            $entities->put($name, $item->toDatamapperObject());
        }

        return $entities;
    }

    /**
     * Convert models to data transfer objects.
     *
     * @param  string  $root
     * @param  array  $schema
     * @param  array  $transformations
     * @param  string  $path
     * @return \AndyH\Datamapper\Support\Collection
     */
    public function toDataTransferObject(string $root, array $schema, array $transformations, $path = '')
    {
        $entities = new DatamapperCollection;

        foreach ($this->items as $name => $item) {
            $entities->put($name, $item->toDataTransferObject($root, $schema, $transformations, $path));
        }

        return $entities;
    }

    /**
     * Convert models to eloquent models.
     *
     * @param  \AndyH\Datamapper\Support\Collection  $entities
     * @param  string  $lastObjectId
     * @param  \AndyH\Datamapper\Eloquent\Model  $lastEloquentModel
     * @return \AndyH\Datamapper\Eloquent\Collection
     */
    public static function newFromDatamapperObject($entities, $lastObjectId, $lastEloquentModel)
    {
        $eloquentModels = new static;

        foreach ($entities as $name => $item) {
            if (spl_object_hash($item) == $lastObjectId) {
                $model = $lastEloquentModel;
            } else {
                $model = Model::newFromDatamapperObject($item, $lastObjectId, $lastEloquentModel);
            }

            $eloquentModels->put($name, $model);
        }

        return $eloquentModels;
    }
}
