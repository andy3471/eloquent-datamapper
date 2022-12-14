<?php

namespace ProAI\Datamapper\Support\Traits;

use Carbon\Carbon;
use ProAI\Datamapper\Annotations as ORM;

trait Timestamps
{
    /**
     * @ORM\Column(type="dateTime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="dateTime")
     */
    protected $updatedAt;

    /**
     * @return \Carbon\Carbon
     */
    public function createdAt()
    {
        return Carbon::instance($this->createdAt->date());
    }

    /**
     * @return \Carbon\Carbon
     */
    public function updatedAt()
    {
        return Carbon::instance($this->updatedAt->date());
    }
}
