<?php

namespace Mission10\CraftcmsFaker\models;
use Mission10\CraftcmsFaker\models\Collection;

class SuperTable extends Collection
{

    public $items;

    public function __construct( $items = null )
    {
        $this->items = $items;
    }

}