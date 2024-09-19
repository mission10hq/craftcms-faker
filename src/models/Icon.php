<?php

namespace Mission10\CraftcmsFaker\models;
use Mission10\CraftcmsFaker\models\Collection;

class Icon extends Collection
{

    public $icon, $sprite, $glyphId, $glyphName, $iconSet, $type, $css, $width, $height;

    public function __construct( $icon = null, $sprite = null, $glyphId = null, $glyphName = null, $iconSet = null, $type = null, $css = null, $width = null, $height = null )
    {
        $this->icon = $icon ?? null;
        $this->sprite = $sprite ?? null;
        $this->glyphId = $glyphId ?? "59389";
        $this->glyphName = $glyphName ?? "unie7fd";
        $this->iconSet = $iconSet ?? "MaterialIconsSharp";
        $this->type = $type ?? "glyph";
        $this->css = $css ?? null;
        $this->width = $width ?? null;
        $this->height = $height ?? null;
    }

    public function __toString()
    {
        if ($this->sprite) {
            return $this->sprite;
        }

        if ($this->glyphId) {
            return $this->glyph;
        }

        if ($this->css) {
            return $this->css;
        }

        if ($this->icon) {
            return $this->getUrl();
        }

        return '';
    }

    public function getLength()
    {
        if ((string)$this === '') {
            return 0;
        }

        return (string)$this;
    }

    public function getIsEmpty()
    {
        return !$this->getLength();
    }

    public function getDimensions($height = null)
    {
        return IconPicker::$plugin->getService()->getDimensions($this->icon, $height);
    }

    public function getUrl()
    {
        return IconPickerHelper::getIconUrl($this->icon);
    }

    public function getPath()
    {
        $settings = IconPicker::$plugin->getSettings();
        $iconSetsPath = $settings->getIconSetsPath();

        $path = FileHelper::normalizePath($iconSetsPath . DIRECTORY_SEPARATOR . $this->icon);

        if (!file_exists($path)) {
            return '';
        }

        return $path;
    }

    public function getInline()
    {
        return IconPicker::$plugin->getService()->inline($this->icon);
    }

    public function getIconName()
    {
        return ($this->icon) ? pathinfo($this->icon, PATHINFO_FILENAME) : '';
    }

    public function getHasIcon()
    {
        return (bool)$this->icon;
    }

    public function getRemoteSet()
    {
        return IconPicker::$plugin->getIconSources()->getRegisteredIconSourceByHandle($this->iconSet);
    }

    public function getSerializedValues()
    {
        if ($this->type === 'sprite') {
            return [
                'type' => 'sprite',
                'name' => $this->iconSet,
                'value' => 'sprite:' . $this->iconSet . ':' . $this->sprite,
                'url' => $this->sprite,
                'label' => $this->sprite,
            ];
        } else if ($this->type === 'glyph') {
            return [
                'type' => 'glyph',
                'name' => $this->iconSet,
                'value' => 'glyph:' . $this->iconSet . ':' . $this->glyphId . ':' . $this->glyphName,
                'url' => $this->getGlyph(),
                'label' => $this->glyphName,
            ];
        } else if ($this->type === 'css') {
            $remoteSet = $this->getRemoteSet();

            return [
                'type' => 'css',
                'name' => $remoteSet['fontName'],
                'value' => 'css:' . $this->iconSet . ':' . $this->css,
                'url' => '',
                'classes' => $remoteSet['classes'] . $this->css,
                'label' => $this->css,
            ];
        }

        return [
            'type' => 'svg',
            'value' => $this->icon,
            'url' => $this->url,
            'label' => $this->iconName,
        ];
    }

    public function getGlyph($format = 'charHex')
    {
        if ($format === 'decimal') {
            return $this->glyphId;
        } else if ($format === 'hex') {
            return dechex($this->glyphId);
        } else if ($format === 'char') {
            return '&#' . $this->glyphId;
        }

        return '&#x' . dechex($this->glyphId);
    }

}