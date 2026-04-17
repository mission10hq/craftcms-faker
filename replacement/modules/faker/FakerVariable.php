<?php

namespace modules\faker;

use modules\faker\models\FakeAsset;
use modules\faker\models\FakeCollection;
use modules\faker\models\FakeDonkeyTail;
use modules\faker\models\FakeEntry;
use modules\faker\models\FakeIcon;
use modules\faker\models\FakeLink;
use modules\faker\models\FakeSuperTable;

class FakerVariable
{
    private function module(): FakerModule
    {
        return FakerModule::getInstance();
    }

    public function asset(?array $attributes = null): FakeAsset
    {
        $module = $this->module();
        $kind = $attributes['kind'] ?? 'image';

        if (isset($attributes['url'])) {
            $url = $attributes['url'];
        } elseif ($kind === 'image') {
            $url = $module->getFakeImageUrl(
                $attributes['source'] ?? null,
                $attributes['width'] ?? null,
                $attributes['height'] ?? null,
                $attributes['id'] ?? null,
                $attributes['text'] ?? null,
                $attributes['query'] ?? null,
            );
        } else {
            $url = $module->getFakeVideoUrl();
        }

        return new FakeAsset(
            $url,
            $attributes['title'] ?? 'Default asset title',
            $attributes['alt'] ?? null,
            $kind,
            $attributes['customFields'] ?? [],
            $attributes['width'] ?? null,
            $attributes['height'] ?? null,
        );
    }

    public function collection(?array $items = null): FakeCollection
    {
        return new FakeCollection($items);
    }

    public function supertable(?array $items = null): FakeSuperTable
    {
        return new FakeSuperTable($items);
    }

    public function link(?array $attributes = null): FakeLink
    {
        return new FakeLink(
            $attributes['url'] ?? null,
            $attributes['text'] ?? null,
            $attributes['target'] ?? null,
        );
    }

    public function entry(?array $attributes = null): FakeEntry
    {
        return new FakeEntry($attributes);
    }

    public function donkeytail(?array $attributes = null): FakeDonkeyTail
    {
        return new FakeDonkeyTail(
            $attributes['url'] ?? 'https://picsum.photos/200',
            $attributes['pins'] ?? [],
        );
    }

    public function navigation(int $totalItems, bool $children = false): FakeCollection
    {
        $items = [];

        for ($i = 1; $i <= $totalItems; $i++) {
            $item = [
                'url' => '#page-' . $i,
                'title' => 'Page ' . $i,
                'active' => $i === 1,
                'newWindow' => false,
                'customAttributes' => [],
            ];

            if ($children) {
                $item['hasDescendants'] = true;
                $childItems = [];
                for ($j = 1; $j < random_int(2, 5); $j++) {
                    $childItems[] = [
                        'url' => '#child-' . $j,
                        'title' => 'Child ' . $j,
                        'active' => ($i === 1 && $j === 1),
                        'newWindow' => false,
                        'customAttributes' => [],
                    ];
                }
                $item['children'] = $childItems;
            }

            $items[] = $item;
        }

        return new FakeCollection($items);
    }

    public function icon(
        ?string $icon = null,
        ?string $sprite = null,
        ?string $glyphId = null,
        ?string $glyphName = null,
        ?string $iconSet = null,
        ?string $type = null,
        ?string $css = null,
        ?int $width = null,
        ?int $height = null,
    ): FakeIcon {
        return new FakeIcon($icon, $sprite, $glyphId, $glyphName, $iconSet, $type, $css, $width, $height);
    }
}
