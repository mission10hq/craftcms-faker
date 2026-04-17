<?php

namespace modules\faker\models;

use Countable;

class FakeCollection implements Countable
{
    public ?array $items;

    public function __construct(?array $items = null)
    {
        $this->items = $items;
    }

    public function all(): array
    {
        return $this->items ?? [$this];
    }

    public function one(): mixed
    {
        if ($this->items !== null) {
            return $this->items[0];
        }
        return $this;
    }

    public function first(): mixed
    {
        return $this->all()[0];
    }

    public function nth(int $n): mixed
    {
        $all = $this->all();
        return array_key_exists($n, $all) ? $all[$n] : $this->first();
    }

    public function limit(int $limit): static
    {
        return new static(array_slice($this->all(), 0, $limit));
    }

    public function offset(int $offset): static
    {
        $all = $this->all();
        return new static(array_slice($all, $offset));
    }

    public function level(int $level): static
    {
        $items = $this->all();
        $newItems = [];

        foreach ($items as $item) {
            if ($level === 2 && ($item['hasDescendants'] ?? false)) {
                $newItems[] = $item['children'];
            } else {
                $newItems = $items;
                break;
            }
        }

        return new static($newItems);
    }

    public function add(mixed $item): static
    {
        $items = $this->items ?? [];
        $item = is_array($item) ? $item : [$item];
        $this->items = array_merge($items, $item);
        return $this;
    }

    public function count(): int
    {
        return count($this->all());
    }
}
