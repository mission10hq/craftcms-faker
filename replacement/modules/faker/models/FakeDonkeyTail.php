<?php

namespace modules\faker\models;

class FakeDonkeyTail extends FakeAsset
{
    public ?string $url;
    public self|null $canvas = null;
    public ?array $pins;

    public function __construct(?string $url = null, ?array $pins = null)
    {
        $this->url = $url;
        $this->canvas = $this;
        $this->pins = $pins;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function getUrl(mixed $attributes = null): ?string
    {
        return $this->url;
    }

    public function setTransform(mixed $attributes = null): static
    {
        return $this;
    }
}
