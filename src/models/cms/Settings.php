<?php

namespace Mission10\CraftcmsFaker\models\cms;

use craft\base\Model;
use craft\helpers\App;

class Settings extends Model
{
    public $source = 'picsum';

    public function getSource(): string
    {
        return App::parseEnv($this->source);
    }

    public function getFakeImageUrl( $source = null, $width = null, $height = null, $id = null, $text = null, $query = null )
    {
        
        $width = $width ?? "200";
        $height = $height ?? "200";
        $source = ($source ?? $this->getSource());
        $source = (in_array($source, ['picsum', 'placeholder', 'unsplash', 'dummyImage', 'local']) ? $source : "picsum");

        if( $source == "local" )
        {
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">'
                . '<rect fill="#e5e7eb" width="' . $width . '" height="' . $height . '"/>'
                . '<text fill="#9ca3af" font-family="system-ui,sans-serif" font-size="14" text-anchor="middle" x="' . ($width / 2) . '" y="' . ($height / 2 + 5) . '">' . $width . ' x ' . $height . '</text>'
                . '</svg>';
            return 'data:image/svg+xml,' . rawurlencode($svg);
        }
        elseif( $source == "unsplash" )
        {
            $url = "https://source.unsplash.com/";
            $url .= $id ?? "random";
            $url .= "/" . $width . "x" . $height . "/";
            if( !is_null($query) ){ 
                $url .= "?" . $query; 
            }
        }
        elseif( $source == "placeholder" || $source == "dummyimage" )
        {
            $url = "https://dummyimage.com/";
            $url .= $width . "x" . $height;
            $url .= "/bdbdbd/f5f5f5";
            $url .= "?text=" . $text ?? ($width . " x " . $height);
        }
        else
        {
            $url = "https://picsum.photos/";
            $url .= $width . "/" . $height;
        }
        
        return $url;

    }

    public function getFakeVideoUrl()
    {
        $baseUrl = "https://raw.githubusercontent.com/jordannbeattie/craftcms-faker/master/src/assets/video/";
        $videos = ['light.mp4', 'dark.mp4'];
        return $baseUrl  . $videos[array_rand($videos)];
    }

}
