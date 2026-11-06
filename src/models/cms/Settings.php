<?php

namespace Mission10\CraftcmsFaker\models\cms;

use craft\base\Model;
use craft\helpers\App;

class Settings extends Model
{
    public $source = 'picsum';

    /** Ground, then gradient stops, for the 'gradient' source. Defaults to the Mission10 palette. */
    public $gradientColours = ['#020006', '#2a17c8', '#6c4dc0', '#c38be0', '#ecc3dc', '#7a96f0', '#1f8fc4'];

    public function getSource(): string
    {
        return App::parseEnv($this->source);
    }

    public function getFakeImageUrl( $source = null, $width = null, $height = null, $id = null, $text = null, $query = null )
    {
        
        $width = $width ?? "200";
        $height = $height ?? "200";
        $source = ($source ?? $this->getSource());
        $source = (in_array($source, ['picsum', 'placeholder', 'unsplash', 'dummyImage', 'local', 'gradient']) ? $source : "picsum");

        if( $source == "gradient" )
        {
            return $this->getGradientPlaceholder((int) $width, (int) $height, $text);
        }
        elseif( $source == "local" )
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

    /**
     * Marbled bands in the brand palette with the size printed in the middle, as an inline SVG:
     * a striped gradient bent by fractal noise, which reads like the iridescent texture without
     * loading an image. The bend is seeded from the size so neighbouring placeholders differ.
     */
    public function getGradientPlaceholder(int $width, int $height, ?string $text = null): string
    {
        $colours = array_values(array_filter((array) $this->gradientColours));
        $ground = $colours[0] ?? '#020006';
        $bands = array_slice($colours, 1) ?: ['#6c4dc0', '#7a96f0'];
        $seed = crc32($width . 'x' . $height . $text);
        $span = max($width, $height);
        $count = count($bands);

        $stops = '';
        foreach (array_merge($bands, $bands) as $i => $colour) {
            $stops .= '<stop offset="' . round($i / (2 * $count - 1), 3) . '" stop-color="' . $colour . '"/>';
        }
        $angle = $seed % 180;
        $label = htmlspecialchars($text ?: ($width . ' × ' . $height), ENT_XML1);
        $size = max(12, min(44, (int) round(min($width, $height) / 10)));

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '" preserveAspectRatio="xMidYMid slice">'
            . '<defs>'
            . '<linearGradient id="b" gradientTransform="rotate(' . $angle . ' .5 .5)">' . $stops . '</linearGradient>'
            . '<radialGradient id="v" r=".75"><stop offset=".35" stop-color="' . $ground . '" stop-opacity="0"/><stop offset="1" stop-color="' . $ground . '" stop-opacity=".75"/></radialGradient>'
            . '<filter id="m" x="-20%" y="-20%" width="140%" height="140%">'
            . '<feTurbulence type="fractalNoise" baseFrequency="' . round(1.6 / $span, 5) . '" numOctaves="2" seed="' . ($seed % 997) . '"/>'
            . '<feDisplacementMap in="SourceGraphic" scale="' . round($span * 0.55) . '" xChannelSelector="R" yChannelSelector="G"/>'
            . '<feGaussianBlur stdDeviation="' . round($span * 0.012, 1) . '"/>'
            . '</filter>'
            . '</defs>'
            . '<rect width="100%" height="100%" fill="' . $ground . '"/>'
            . '<rect x="-20%" y="-20%" width="140%" height="140%" fill="url(#b)" filter="url(#m)" opacity=".8"/>'
            . '<rect width="100%" height="100%" fill="url(#v)"/>'
            . '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#f6f5f5" fill-opacity=".9" font-family="system-ui,-apple-system,Segoe UI,sans-serif" font-weight="700" letter-spacing=".12em" font-size="' . $size . '">' . $label . '</text>'
            . '</svg>';

        return 'data:image/svg+xml,' . rawurlencode($svg);
    }

    public function getFakeVideoUrl()
    {
        $baseUrl = "https://raw.githubusercontent.com/jordannbeattie/craftcms-faker/master/src/assets/video/";
        $videos = ['light.mp4', 'dark.mp4'];
        return $baseUrl  . $videos[array_rand($videos)];
    }

}
