<?php

namespace Mission10\CraftcmsFaker\models;
use DateTime;
use Mission10\CraftcmsFaker\models\Asset;
use Mission10\CraftcmsFaker\models\Collection;

class Entry extends Collection
{

    public $attributes;

    public function __construct( $attributes = null )
    {
        $this->attributes = $attributes ?? [];
    }

    public function getThumbnail()
    {
        return $this->image;
    }

    public function get()
    {
        $class = new \StdClass();
        foreach( $this->attributes as $key => $value )
        {
            $class->$key = $value;
        }
        $this->addStandardProperties($class);
        return $class;
    }

    private function addStandardProperties($class)
    {

        $defaults = [
            'title' => 'Fake entry', 
            'postDate' => date('now'), 
            'slug' => 'fake-entry', 
            'url' => '#'
        ];

        foreach ($defaults as $property => $value) {
            if(!property_exists($class, $property))
            {
                $class->$property = $value;
            }
        }


    }

}