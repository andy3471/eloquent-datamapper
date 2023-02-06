<?php

namespace AndyH\Datamapper\Repositories;

use Illuminate\Database\Eloquent\Collection;
use AndyH\Datamapper\EntityManager;

class EntityRepository
{
    /**
     * @var string
     */
    private string $entityName;

    /**
     * @var EntityManager
     */
    private EntityManager $em;

    /**
     * @var string
     */
    private $_class;

    /**
     * @param  EntityManager  $em
     * @param  ClassMetadata  $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->_class = $class;
    }

    /**
     * @return Collection|EntityManager
     */
    public function query()
    {
        return $this->em->entity($this->_class);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->em->entity($this->_class)->find($id);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->em->entity($this->_class)->first();
    }

    /**
     * @param $entity
     * @return null
     */
    public function insert($entity)
    {
        return $this->em->insert($entity);
    }

    /**
     * @param $entity
     * @return null
     */
    public function update($entity)
    {
        return $this->em->update($entity);
    }

    /**
     * @param $entity
     * @return null
     */
    public function delete($entity)
    {
        return $this->em->delete($entity);
    }
}
