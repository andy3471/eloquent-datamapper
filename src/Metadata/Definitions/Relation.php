<?php namespace Wetzel\Datamapper\Metadata\Definitions;

class Relation extends Definition {
    
    /**
     * Valid keys.
     * 
     * @var array
     */
    $keys = [
        'name' => null,
        'type' => null,
        'relatedClass' => null,
        'pivotTable' => null;
        'options' => [],
    ];

}