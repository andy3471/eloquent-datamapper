<?php namespace Wetzel/Datamapper/Annotations/Relation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class MorphOne extends Relation implements Annotation
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type = null;

    /**
     * @var string
     */
    public $id = null;

    /**
     * @var string
     */
    public $localKey = null;
}