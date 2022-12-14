<?php

namespace ProAI\Datamapper;

use Exception;
use ProAI\Datamapper\Eloquent\Builder;
use ProAI\Datamapper\Eloquent\Model;

class EntityManager
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get a new datamapper query instance.
     *
     * @param  string  $class
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function entity($class)
    {
        $class = get_real_entity($class);

        $eloquentModel = get_mapped_model($class);

        return (new $eloquentModel)->newQuery(Builder::RETURN_TYPE_DATAMAPPER);
    }

    /**
     * Get a new schema query instance.
     *
     * @param  string  $class
     * @return \ProAI\Datamapper\Eloquent\GraphBuilder
     */
    public function newGraphQuery($class)
    {
        $class = get_real_entity($class);

        $eloquentModel = get_mapped_model($class);

        return (new $eloquentModel)->newGraphQuery();
    }

    /**
     * Create an entity object.
     *
     * @param  object  $entity
     * @return void
     */
    public function insert($entity)
    {
        $eloquentModel = $this->getEloquentModel($entity);

        $this->updateRelations($eloquentModel, 'insert');

        $eloquentModel->save();

        $eloquentModel->afterSaving($entity, 'insert');
    }

    /**
     * Update an entity object.
     *
     * @param  object  $entity
     * @return void
     */
    public function update($entity)
    {
        $eloquentModel = $this->getEloquentModel($entity, true);

        $this->updateRelations($eloquentModel, 'update');

        $eloquentModel->save();

        $eloquentModel->afterSaving($entity, 'update');
    }

    /**
     * Delete an entity object.
     *
     * @param  object  $entity
     * @return void
     */
    public function delete($entity)
    {
        $eloquentModel = $this->getEloquentModel($entity, true);

        $this->updateRelations($eloquentModel, 'delete');

        $eloquentModel->delete();
    }

    /**
     * Update relations.
     *
     * @param  \ProAI\Datamapper\Eloquent\Model  $eloquentModel
     * @param  string  $action
     * @return void
     */
    protected function updateRelations($eloquentModel, $action)
    {
        $mapping = $eloquentModel->getMapping();
        $eloquentRelations = $eloquentModel->getRelations();

        foreach ($mapping['relations'] as $name => $relationMapping) {
            if (isset($eloquentRelations[$name])) {
                $this->updateRelation($eloquentModel, $name, $relationMapping, $action);
            }
        }
    }

    /**
     * Update a relation.
     *
     * @param  \ProAI\Datamapper\Eloquent\Model  $eloquentModel
     * @param  string  $name
     * @param  array  $relationMapping
     * @param  string  $action
     * @return void
     */
    protected function updateRelation($eloquentModel, $name, $relationMapping, $action)
    {
        // set foreign key for belongsTo/morphTo relation
        if ($relationMapping['type'] == 'belongsTo' || $relationMapping['type'] == 'morphTo') {
            $this->updateBelongsToRelation($eloquentModel, $name, $action);
        }

        // set foreign keys for belongsToMany/morphToMany relation
        if (($relationMapping['type'] == 'belongsToMany' || $relationMapping['type'] == 'morphToMany') && ! $relationMapping['inverse']) {
            $this->updateBelongsToManyRelation($eloquentModel, $name, $action);
        }
    }

    /**
     * Update a belongsTo or morphTo relation.
     *
     * @param  \ProAI\Datamapper\Eloquent\Model  $eloquentModel
     * @param  string  $name
     * @param  string  $action
     * @return void
     */
    protected function updateBelongsToRelation($eloquentModel, $name, $action)
    {
        if ($action == 'insert' || $action == 'update') {
            $eloquentModel->{$name}()->associate($eloquentModel->getRelation($name));
        }
    }

    /**
     * Update a belongsToMany or morphToMany relation.
     *
     * @param  \ProAI\Datamapper\Eloquent\Model  $eloquentModel
     * @param  string  $name
     * @param  string  $action
     * @return void
     */
    protected function updateBelongsToManyRelation($eloquentModel, $name, $action)
    {
        $eloquentCollection = $eloquentModel->getRelation($name);

        if (! $eloquentCollection instanceof \Illuminate\Database\Eloquent\Collection) {
            throw new Exception("Many-to-many relation '".$name."' is not a valid collection");
        }

        // get related keys
        $keys = [];

        foreach ($eloquentCollection as $item) {
            $keys[] = $item->getKey();
        }

        // attach/sync/detach keys
        if ($action == 'insert') {
            $eloquentModel->{$name}()->attach($keys);
        }
        if ($action == 'update') {
            $eloquentModel->{$name}()->sync($keys);
        }
        if ($action == 'delete') {
            $eloquentModel->{$name}()->detach($keys);
        }
    }

    /**
     * Delete an entity object.
     *
     * @param  object  $entity
     * @return \ProAI\Datamapper\Eloquent\Model
     */
    protected function getEloquentModel($entity, $exists = false)
    {
        if (empty($entity)) {
            throw new Exception('Object transfered to EntityManager is empty');
        }

        if (! is_object($entity)) {
            throw new Exception('Object transfered to EntityManager is not an object');
        }

        $eloquentModel = Model::newFromDatamapperObject($entity);

        $eloquentModel->exists = $exists;

        return $eloquentModel;
    }
}
