<?php

namespace modules\faker\models;

use Stringable;

class FakeIcon extends FakeCollection implements Stringable
{
    public ?string $icon;
    public ?string $sprite;
    public string $glyphId;
    public string $glyphName;
    public string $iconSet;
    public string $type;
    public ?string $css;
    public ?int $width;
    public ?int $height;

    public function __construct(
        ?string $icon = null,
        ?string $sprite = null,
        ?string $glyphId = null,
        ?string $glyphName = null,
        ?string $iconSet = null,
        ?string $type = null,
        ?string $css = null,
        ?int $width = null,
        ?int $height = null,
    ) {
        $this->icon = $icon;
        $this->sprite = $sprite;
        $this->glyphId = $glyphId ?? '59389';
        $this->glyphName = $glyphName ?? 'unie7fd';
        $this->iconSet = $iconSet ?? 'MaterialIconsSharp';
        $this->type = $type ?? 'glyph';
        $this->css = $css;
        $this->width = $width;
        $this->height = $height;
    }

    public function __toString(): string
    {
        if ($this->sprite) {
            return $this->sprite;
        }

        if ($this->glyphId) {
            return $this->getGlyph();
        }

        if ($this->css) {
            return $this->css;
        }

        if ($this->icon) {
            return $this->icon;
        }

        return '';
    }

    public function getLength(): string|int
    {
        $str = (string) $this;
        return $str === '' ? 0 : $str;
    }

    public function getIsEmpty(): bool
    {
        return !$this->getLength();
    }

    public function getIconName(): string
    {
        return $this->icon ? pathinfo($this->icon, PATHINFO_FILENAME) : '';
    }

    public function getHasIcon(): bool
    {
        return (bool) $this->icon;
    }

    public function getGlyph(string $format = 'charHex'): string
    {
        if ($format === 'decimal') {
            return $this->glyphId;
        }

        if ($format === 'hex') {
            return dechex((int) $this->glyphId);
        }

        if ($format === 'char') {
            return '&#' . $this->glyphId;
        }

        return '&#x' . dechex((int) $this->glyphId);
    }

    public function getSerializedValues(): array
    {
        if ($this->type === 'sprite') {
            return [
                'type' => 'sprite',
                'name' => $this->iconSet,
                'value' => 'sprite:' . $this->iconSet . ':' . $this->sprite,
                'url' => $this->sprite,
                'label' => $this->sprite,
            ];
        }

        if ($this->type === 'glyph') {
            return [
                'type' => 'glyph',
                'name' => $this->iconSet,
                'value' => 'glyph:' . $this->iconSet . ':' . $this->glyphId . ':' . $this->glyphName,
                'url' => $this->getGlyph(),
                'label' => $this->glyphName,
            ];
        }

        if ($this->type === 'css') {
            return [
                'type' => 'css',
                'name' => $this->iconSet,
                'value' => 'css:' . $this->iconSet . ':' . $this->css,
                'url' => '',
                'classes' => $this->css,
                'label' => $this->css,
            ];
        }

        return [
            'type' => 'svg',
            'value' => $this->icon,
            'url' => $this->icon,
            'label' => $this->getIconName(),
        ];
    }
}
