<?php

namespace AndyH\Datamapper\Contracts;

interface ValueObject extends Model
{
    /**
     * Compare two value objects.
     *
     * @param  \AndyH\Datamapper\Support\ValueObject  $valueObject
     * @return bool
     */
    public function equals(ValueObject $valueObject);
}
