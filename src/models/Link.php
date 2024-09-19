<?php

namespace Mission10\CraftcmsFaker\models;
use Mission10\CraftcmsFaker\models\Collection;

class Link extends Collection
{

    public $url, $text, $target;

    public function __construct( $url = null, $text = null, $target = null )
    {
        $this->url = $url ?? '#';
        $this->text = $text ?? "Learn more";
        $this->target = $target ?? null;
    }

    public function url()
    {
        return $this->url;
    }
    
    public function text()
    {
        return $this->text;
    }

    public function target()
    {
        return is_null($this->target) ? false : $this->target;
    }

    public function getUrl()
    {
        return $this->url();
    }

    public function getText()
    {
        return $this->text();
    }

    public function getTarget()
    {
        return $this->target();
    }

    public function isEmpty()
    {
        return false;
    }

}