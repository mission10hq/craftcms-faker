<?php

namespace Mission10\CraftcmsFaker\variables;
use Craft;
use Mission10\CraftcmsFaker\Faker;
use Mission10\CraftcmsFaker\models\Asset;
use Mission10\CraftcmsFaker\models\Collection;
use Mission10\CraftcmsFaker\models\DonkeyTail;
use Mission10\CraftcmsFaker\models\Entry;
use Mission10\CraftcmsFaker\models\Icon;
use Mission10\CraftcmsFaker\models\Link;
use Mission10\CraftcmsFaker\models\SuperTable;

Class FakerVariable
{
    
    public function asset( $attributes = null )
    {
        $settings = Faker::getInstance()->getSettings();
        $fakeImageUrl = $settings->getFakeImageUrl( 
            $attributes['source'] ?? null,
            $attributes['width'] ?? null, 
            $attributes['height'] ?? null,
            $attributes['id'] ?? null,
            $attributes['text'] ?? null,
            $attributes['query'] ?? null,
        );
        $fakeVideoUrl = $settings->getFakeVideoUrl();
        $kind = $attributes['kind'] ?? "image";
        $url = $attributes['url'] ?? ( $kind == "image" ? $fakeImageUrl : $fakeVideoUrl );
        $title = $attributes['title'] ?? 'Default asset title';
        $alt = $attributes['alt'] ?? $title;
        $customFields = $attributes['customFields'] ?? [];

        return new Asset($url, $title, $alt, $kind, $customFields);
    }

    public function collection( $items = null )
    {
        return new Collection( $items ); 
    }

    public function supertable( $items = null )
    {
        return new SuperTable( $items );
    }

    public function link( $attributes = null )
    {
        $url = $attributes['url'] ?? null;
        $text = $attributes['text'] ?? null;
        $target = $attributes['target'] ?? null;
        return new Link( $url, $text, $target );
    }

    public function entry( $attributes = null )
    {
        return new Entry( $attributes );
    }

    public function donkeytail( $attributes = null )
    {
        $url = $attributes['url'] ?? 'https://picsum.photos/200';
        $pins = $attributes['pins'] ?? [];
        return new DonkeyTail($url, $pins);
    }

    public function navigation( $totalItems, $children = false )
    {
        $items = [];
        for ($i=1; $i < $totalItems+1; $i++) { 
            $item = [
                'url' => '#page-' . $i,
                'title' => 'Page ' . $i,
                'active' => $i == 1,
                'newWindow' => false, 
                'customAttributes' => []
            ];
            if( $children )
            {
                $item['hasDescendants'] = true;
                $children = [];
                for ($j=1; $j < random_int(2, 5); $j++) { 
                    array_push($children, [
                        'url' => '#child-' . $j,
                        'title' => 'Child ' . $j,
                        'active' => ($i == 1 && $j == 1),
                        'newWindow' => false, 
                        'customAttributes' => []
                    ]);
                }
                $item['children'] = $children;
            }
            array_push($items, $item);
        }
        return new Collection($items);
    }

    public function icon($icon = null, $sprite = null, $glyphId = null, $glyphName = null, $iconSet = null, $type = null, $css = null, $width = null, $height = null)
    {
        return new Icon($icon, $sprite, $glyphId, $glyphName, $iconSet, $type, $css, $width, $height);
    }
    
}
