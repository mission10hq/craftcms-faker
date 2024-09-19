<?php

namespace Mission10\CraftcmsFaker\models;
use Mission10\CraftcmsFaker\models\Collection;

class Asset extends Collection implements \ArrayAccess
{

    public $url, $title, $alt, $extension, $kind;

    private $attributes = [];

    public function __construct( $url = null, $title = null, $alt = null, $kind = null, $customFields = [] )
    {
        $this->url = $url ?? null;
        $this->title = $title ?? null;
        $this->alt = $alt ?? ($this->title ?? null);
        $this->extension = "png";
        $this->kind = $kind ?? "image";
        $this->setCustomFields( $customFields );
    }

    public function url()
    {
        return $this->url;
    }

    public function getUrl($attributes=null)
    {
        return $this->url;
    }

    public function setTransform($attributes=null)
    {
        return $this;
    }

    public function setCustomFields( $customFields = [] )
    {
        foreach( $customFields as $key => $value )
        {
            $this->$key = $value;
        }
    }

    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function __get($name) {

        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    // ArrayAccess methods
    public function offsetExists($offset):bool {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset) {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void {
        unset($this->attributes[$offset]);
    }

}