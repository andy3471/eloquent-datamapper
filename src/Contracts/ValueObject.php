<?php

namespace ProAI\Datamapper\Contracts;

interface ValueObject extends Model
{
    /**
     * Compare two value objects.
     *
     * @param  \ProAI\Datamapper\Support\ValueObject  $valueObject
     * @return bool
     */
    public function equals(ValueObject $valueObject);
}
