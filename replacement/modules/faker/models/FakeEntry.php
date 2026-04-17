<?php

namespace modules\faker\models;

use stdClass;

class FakeEntry extends FakeCollection
{
    public ?array $attributes;

    public function __construct(?array $attributes = null)
    {
        $this->attributes = $attributes ?? [];
    }

    public function getThumbnail(): mixed
    {
        return $this->image;
    }

    public function get(): stdClass
    {
        $obj = new stdClass();

        foreach ($this->attributes as $key => $value) {
            $obj->$key = $value;
        }

        $defaults = [
            'title' => 'Fake entry',
            'postDate' => date('Y-m-d H:i:s'),
            'slug' => 'fake-entry',
            'url' => '#',
        ];

        foreach ($defaults as $property => $value) {
            if (!property_exists($obj, $property)) {
                $obj->$property = $value;
            }
        }

        return $obj;
    }
}
