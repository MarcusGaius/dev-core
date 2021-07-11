<?php

namespace Developion\Core\fields;

use Craft;
use craft\base\Field;
use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;

class BaseLink extends Field
{
    const LINKTYPES = [
        'asset',
        'entry',
        'url',
        'email',
        'phone',
    ];

    public $target;

    public $linkType;

    public $buttonText;

    public $buttonLink;

    protected $linkTypes = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->buttonText   = Craft::t('core', 'Learn More');
        $this->buttonLink   = '#';
        $this->target       = false;
        $this->linkType     = 'url';

        $this->linkTypes = ArrayHelper::map(
            $this::LINKTYPES,
            function($linkType) {
                return $linkType;
            },
            function($linkType) {
                return $linkType == 'url' ? Craft::t('core', StringHelper::toUpperCase($linkType)) : Craft::t('core', StringHelper::toTitleCase($linkType));
            }
        );
        parent::init();
    }
}
