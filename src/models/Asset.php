<?php

namespace Mission10\CraftcmsFaker\models;
use Mission10\CraftcmsFaker\models\Collection;

class Asset extends Collection implements \ArrayAccess
{

    public $url, $title, $alt, $extension, $kind, $mimeType, $filename;
    public $width, $height;

    private $attributes = [];

    public function __construct( $url = null, $title = null, $alt = null, $kind = null, $customFields = [], $width = null, $height = null )
    {
        $this->url = $url ?? null;
        $this->title = $title ?? null;
        $this->alt = $alt ?? ($this->title ?? null);
        $this->extension = "png";
        $this->kind = $kind ?? "image";
        $this->mimeType = "image/png";
        $this->filename = "fake-image.png";
        $this->width = $width ?? 200;
        $this->height = $height ?? 200;
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

    public function width()
    {
        return $this->width;
    }

    public function height()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setTransform($attributes=null)
    {
        if (is_array($attributes)) {
            if (isset($attributes['width'])) {
                $this->width = $attributes['width'];
            }
            if (isset($attributes['height'])) {
                $this->height = $attributes['height'];
            }
        }
        return $this;
    }

    public function getSrcset($sizes = [])
    {
        $parts = [];
        foreach ($sizes as $size) {
            $w = intval($size);
            if ($w > 0) {
                $parts[] = $this->url . ' ' . $w . 'w';
            }
        }
        return implode(', ', $parts);
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