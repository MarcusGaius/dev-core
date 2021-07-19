<?php

namespace Developion\Core;

use Craft;
use craft\base\Field;
use craft\base\Plugin;
use craft\events\FieldElementEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\ElementHelper;
use craft\i18n\PhpMessageSource;
use craft\services\Fields;
use craft\web\UrlManager;
use Developion\Core\fields\TestField;
use Developion\Core\fields\ThumbnailLink;
use Developion\Core\models\FieldConfigData;
use yii\base\Event;

class Core extends Plugin
{
    public static $plugin;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $request = Craft::$app->getRequest();

        if ($request->getIsCpRequest()) {
            $this->_cpEvents();
        } else {
            $this->_siteEvents();
            $this->_twigExtensions();
        }

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ThumbnailLink::class;
                $event->types[] = TestField::class;
            }
        );

        Craft::$app->i18n->translations['core'] = [
            'class' => PhpMessageSource::class,
            'basePath' => __DIR__ . '/translations',
            'allowOverrides' => true,
            'forceTranslation' => true
        ];
    }

    
    protected function _cpEvents()
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
            }
        );
        Event::on(
            Field::class,
            Field::EVENT_BEFORE_ELEMENT_SAVE,
            function (FieldElementEvent $event) {
                // Craft::dd(Craft::$app->request);
                if (
                    $event->sender instanceof TestField &&
                    in_array($event->sender->handle, $event->element->getDirtyFields())
                ) {
                    $event->element->getIsUnpublishedDraft();
                    // Craft::dd($event->element->getBehavior('customFields')->{$event->sender->handle});
                    (new FieldConfigData($event->sender, $event->element))
                        ->setRecord();
                }
            }
        );
        Event::on(
            Field::class,
            Field::EVENT_AFTER_ELEMENT_SAVE,
            function (FieldElementEvent $event) {
                
                if (
                    $event->sender instanceof TestField &&
                    in_array($event->sender->handle, $event->element->getDirtyFields())
                ) {
                    (new FieldConfigData($event->sender, $event->element))
                        ->setOwnerId();
                }
            }
        );
    }

    protected function _siteEvents()
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                // $event->rules['previews/academy-card/<slug:{slug}>']    = 'developion-core/preview/academy-card';
            }
        );
    }

    protected function _twigExtensions()
    {
        // Craft::$app->view->registerTwigExtension(new DevelopionTwigExtension);
    }
}
