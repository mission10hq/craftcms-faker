<?php

namespace modules\faker\models;

use Twig\Markup;

class FakeLink extends FakeCollection
{
    public string $url;
    public string $text;
    public ?string $target;

    public function __construct(?string $url = null, ?string $text = null, ?string $target = null)
    {
        $this->url = $url ?? '#';
        $this->text = $text ?? 'Learn more';
        $this->target = $target;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function label(): string
    {
        return $this->text;
    }

    public function target(): string|false
    {
        return $this->target ?? false;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTarget(): string|false
    {
        return $this->target();
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function getLinkAttributes(?array $extraAttributes = null): Markup
    {
        $attrs = 'href="' . htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8') . '"';

        if ($this->target) {
            $attrs .= ' target="' . htmlspecialchars($this->target, ENT_QUOTES, 'UTF-8') . '"';
        }

        if (is_array($extraAttributes)) {
            foreach ($extraAttributes as $key => $value) {
                $attrs .= ' ' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }

        return new Markup($attrs, 'UTF-8');
    }
}
