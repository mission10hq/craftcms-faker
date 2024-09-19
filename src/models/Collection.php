<?php

namespace Mission10\CraftcmsFaker\models;
use Countable;
use craft\base\Model;

class Collection extends Model implements Countable
{

    public $items;

    public function __construct( $items = null )
    {
        $this->items = $items;
    }

    public function all()
    {
        if( !is_null($this->items) )
        {
            return $this->items;
        }
        return [$this];
    }

    public function one()
    {
        if(!is_null($this->items) )
        {
            return $this->items[0];
        }
        return $this; 
    }

    public function limit($limit)
    {
        $all_items = $this->all();
        $limited_items = array_splice($all_items, 0, $limit);
        return new Collection($limited_items);
    }

    public function count()
    {
        return count($this->all());
    }

    public function first()
    {
        return $this->all()[0];
    }

    public function nth($n)
    {
        $all = $this->all();
        return array_key_exists($n, $all) ? $all[$n] : $this->first();
    }

    public function offset($offset)
    {
        $all_items = $this->all();
        $limited_items = array_splice($all_items, $offset, count($all_items));
        return new Collection($limited_items);
    }

    public function level($level)
    {
        $items = $this->all();
        $newItems = [];
        foreach( $items as $item )
        {
            if( $level == 2 )
            {
                if( $item['hasDescendants'] )
                {
                    array_push($newItems, $item['children']);
                }
            }
            else
            {
                $newItems = $items;
            }
        }
        return new Collection($newItems);
    }

    public function add( $item )
    {
        $items = $this->items ?? [];
        $item = is_array($item) ? $item : [$item];
        $this->items = array_merge($items, $item);
        return $this;
    }

}