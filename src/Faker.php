<?php

namespace Mission10\CraftcmsFaker;
use \craft\web\twig\variables\CraftVariable;
use craft\base\Model;
use Mission10\CraftcmsFaker\models\cms\Settings;
use Mission10\CraftcmsFaker\variables\FakerVariable;
use yii\base\Event;

class Faker extends \craft\base\Plugin
{

    public bool $hasCpSettings = true;

    public function init()
    {
        
        parent::init();

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('faker', FakerVariable::class);
            }
        );

    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return \Craft::$app->getView()->renderTemplate(
            'faker/settings',
            [ 'settings' => $this->getSettings() ]
        );
    }

}
