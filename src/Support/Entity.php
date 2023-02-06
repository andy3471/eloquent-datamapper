<?php

namespace AndyH\Datamapper\Support;

use AndyH\Datamapper\Contracts\Entity as EntityContract;
use AndyH\Datamapper\Eloquent\Collection as EloquentCollection;
use AndyH\Datamapper\Eloquent\Model as EloquentModel;
use ReflectionClass;

abstract class Entity extends Model implements EntityContract
{
    /**
     * Build new instance from an eloquent model object.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $eloquentModel
     * @return \AndyH\Datamapper\Support\Entity
     */
    public static function newFromEloquentObject(EloquentModel $eloquentModel)
    {
        $rc = new ReflectionClass(static::class);
        $entity = $rc->newInstanceWithoutConstructor();

        // get model data
        $dict = [
            'mapping' => $eloquentModel->getMapping(),
            'attributes' => $eloquentModel->getAttributes(),
            'relations' => $eloquentModel->getRelations(),
        ];

        // attributes
        foreach ($dict['mapping']['attributes'] as $attribute => $column) {
            $entity->{$attribute} = $dict['attributes'][$column];
        }

        // embeddeds
        foreach ($dict['mapping']['embeddeds'] as $name => $embedded) {
            $entity->{$name} = $embedded['class']::newFromEloquentObject($eloquentModel, $name);
        }

        // relations
        foreach ($dict['mapping']['relations'] as $name => $relation) {
            // set relation object
            if (! empty($dict['relations'][$name])) {
                $relationObject = $dict['relations'][$name]->toDatamapperObject();
            } elseif (in_array($relation['type'], $eloquentModel->manyRelations)) {
                $relationObject = new ProxyCollection;
            } else {
                $relationObject = new Proxy;
            }

            $entity->{$name} = $relationObject;
        }

        return $entity;
    }

    /**
     * Convert an instance to an eloquent model object.
     *
     * @param  string  $lastObjectId
     * @param  \AndyH\Datamapper\Eloquent\Model  $lastEloquentModel
     * @return \AndyH\Datamapper\Eloquent\Model
     */
    public function toEloquentObject($lastObjectId, $lastEloquentModel)
    {
        $class = get_mapped_model(static::class);

        $eloquentModel = new $class;

        // get model data
        $dict = [
            'mapping' => $eloquentModel->getMapping(),
            'attributes' => $eloquentModel->getAttributes(),
            'relations' => $eloquentModel->getRelations(),
        ];

        // attributes
        foreach ($dict['mapping']['attributes'] as $attribute => $column) {
            if (! $eloquentModel->isAutomaticallyUpdatedDate($column)) {
                $eloquentModel->setAttribute($column, $this->{$attribute});
            }
        }

        // embeddeds
        foreach ($dict['mapping']['embeddeds'] as $name => $embedded) {
            $embeddedObject = $this->{$name};

            if (! empty($embeddedObject)) {
                $embeddedObject->toEloquentObject($eloquentModel, $name);
            }
        }

        // relations
        foreach ($dict['mapping']['relations'] as $name => $relation) {
            $relationObject = $this->{$name};

            if (! empty($relationObject) && ! $relationObject instanceof \AndyH\Datamapper\Contracts\Proxy) {
                // set relation
                if ($relationObject instanceof \AndyH\Datamapper\Support\Collection) {
                    $value = EloquentCollection::newFromDatamapperObject($relationObject, $this, $eloquentModel);
                } elseif (spl_object_hash($relationObject) == $lastObjectId) {
                    $value = $lastEloquentModel;
                } else {
                    $value = EloquentModel::newFromDatamapperObject($relationObject, spl_object_hash($this), $eloquentModel);
                }

                $eloquentModel->setRelation($name, $value);
            }
        }

        return $eloquentModel;
    }
}
