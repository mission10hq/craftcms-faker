<?php

namespace modules\faker\twigextensions;

use modules\faker\FakerModule;
use modules\faker\models\FakeAsset;
use modules\faker\models\FakeCollection;
use modules\faker\models\FakeEntry;
use modules\faker\models\FakeLink;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FakerTwigExtension extends AbstractExtension
{
    private FakerModule $module;

    public function __construct(FakerModule $module)
    {
        $this->module = $module;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('faker_image', [$this, 'fakeImageUrl']),
            new TwigFunction('faker_video', [$this, 'fakeVideoUrl']),
        ];
    }

    public function fakeImageUrl(
        int|string|null $width = 200,
        int|string|null $height = 200,
        ?string $source = null,
    ): string {
        return $this->module->getFakeImageUrl($source, $width, $height);
    }

    public function fakeVideoUrl(): string
    {
        return $this->module->getFakeVideoUrl();
    }
}
