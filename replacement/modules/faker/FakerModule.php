<?php

namespace modules\faker;

use Craft;
use craft\web\twig\variables\CraftVariable;
use modules\faker\twigextensions\FakerTwigExtension;
use yii\base\Event;

class FakerModule extends \yii\base\Module
{
    public string $imageSource = 'picsum';

    public function init(): void
    {
        parent::init();

        Craft::setAlias('@modules/faker', __DIR__);

        if (Craft::$app->getRequest()->getIsSiteRequest()) {
            Craft::$app->getView()->registerTwigExtension(new FakerTwigExtension($this));
        }

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $event->sender->set('faker', FakerVariable::class);
            }
        );
    }

    public function getFakeImageUrl(
        ?string $source = null,
        int|string|null $width = null,
        int|string|null $height = null,
        ?string $id = null,
        ?string $text = null,
        ?string $query = null,
    ): string {
        $width = $width ?? 200;
        $height = $height ?? 200;
        $source = $source ?? $this->imageSource;

        if (!in_array($source, ['picsum', 'placeholder', 'unsplash', 'dummyImage', 'local'])) {
            $source = 'picsum';
        }

        if ($source === 'local') {
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">'
                . '<rect fill="#e5e7eb" width="' . $width . '" height="' . $height . '"/>'
                . '<text fill="#9ca3af" font-family="system-ui,sans-serif" font-size="14" text-anchor="middle" x="' . ($width / 2) . '" y="' . ($height / 2 + 5) . '">' . $width . ' x ' . $height . '</text>'
                . '</svg>';
            return 'data:image/svg+xml,' . rawurlencode($svg);
        }

        if ($source === 'unsplash') {
            $url = 'https://source.unsplash.com/' . ($id ?? 'random') . '/' . $width . 'x' . $height . '/';
            if ($query !== null) {
                $url .= '?' . $query;
            }
            return $url;
        }

        if ($source === 'placeholder' || $source === 'dummyImage') {
            return 'https://dummyimage.com/' . $width . 'x' . $height . '/bdbdbd/f5f5f5?text=' . ($text ?? ($width . ' x ' . $height));
        }

        return 'https://picsum.photos/' . $width . '/' . $height;
    }

    public function getFakeVideoUrl(): string
    {
        $baseUrl = 'https://raw.githubusercontent.com/jordannbeattie/craftcms-faker/master/src/assets/video/';
        $videos = ['light.mp4', 'dark.mp4'];
        return $baseUrl . $videos[array_rand($videos)];
    }
}
