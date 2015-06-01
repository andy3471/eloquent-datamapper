<?php namespace Wetzel\Datamapper\Metadata\Definitions;

class EmbeddedClass extends Definition {
    
    /**
     * Valid keys.
     * 
     * @var array
     */
    protected $keys = [
        'name' => null,
        'embeddedClass' => null,
        'attributes' => [],
    ];

}