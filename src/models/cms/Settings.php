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
        $source = (in_array($source, ['picsum', 'placeholder', 'unsplash', 'dummyImage']) ? $source : "picsum");

        if( $source == "unsplash" )
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
