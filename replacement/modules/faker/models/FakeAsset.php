<?php

namespace modules\faker\models;

use ArrayAccess;

class FakeAsset extends FakeCollection implements ArrayAccess
{
    public ?string $url;
    public ?string $title;
    public ?string $alt;
    public string $extension = 'png';
    public ?string $kind;
    public string $mimeType = 'image/png';
    public string $filename = 'fake-image.png';
    public int|string|null $width;
    public int|string|null $height;

    private array $attributes = [];

    public function __construct(
        ?string $url = null,
        ?string $title = null,
        ?string $alt = null,
        ?string $kind = null,
        array $customFields = [],
        int|string|null $width = null,
        int|string|null $height = null,
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->alt = $alt ?? $title;
        $this->kind = $kind ?? 'image';
        $this->width = $width ?? 200;
        $this->height = $height ?? 200;

        foreach ($customFields as $key => $value) {
            $this->$key = $value;
        }
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function getUrl(mixed $attributes = null): ?string
    {
        return $this->url;
    }

    public function width(): int|string|null
    {
        return $this->width;
    }

    public function height(): int|string|null
    {
        return $this->height;
    }

    public function getWidth(): int|string|null
    {
        return $this->width;
    }

    public function getHeight(): int|string|null
    {
        return $this->height;
    }

    public function setTransform(mixed $attributes = null): static
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

    public function getSrcset(array $sizes = []): string
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

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }
}
