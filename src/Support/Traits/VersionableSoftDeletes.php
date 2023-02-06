<?php

namespace AndyH\Datamapper\Support\Traits;

use Carbon\Carbon;
use AndyH\Datamapper\Annotations as ORM;

trait VersionableSoftDeletes
{
    /**
     * @ORM\Column(type="dateTime", nullable=true)
     * @ORM\Versioned
     */
    protected $deletedAt;

    /**
     * @return \Carbon\Carbon
     */
    public function deletedAt()
    {
        return Carbon::instance($this->deletedAt->date());
    }
}
