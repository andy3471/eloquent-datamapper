<?php

namespace ProAI\Datamapper\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Column implements Annotation
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $primary = false;

    /**
     * @var bool
     */
    public $unique = false;

    /**
     * @var bool
     */
    public $index = false;

    /**
     * @var bool
     */
    public $nullable = false;

    /**
     * @var mixed
     */
    public $default;

    /**
     * @var bool
     */
    public $unsigned = false;

    /**
     * @var int
     */
    public $length = 255;

    /**
     * @var bool
     */
    public $fixed = false;

    /**
     * @var int
     */
    public $scale = 8;

    /**
     * @var int
     */
    public $precision = 2;

    /**
     * @var bool
     */
    public $autoIncrement = false;

    /**
     * @var bool
     */
    public $autoUuid = false;

    /**
     * @var bool
     */
    public $versioned = false;
}
