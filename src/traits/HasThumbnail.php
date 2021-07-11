<?php

namespace Developion\Core\traits;

use Craft;
use craft\elements\Asset;
use craft\helpers\Cp;

trait HasThumbnail
{
    public $thumbnail;

    public function getThumbnailHtml($value, string $id): string
    {
        return Cp::fieldHtml('template:_includes/forms/elementSelect', [
            'label' => Craft::t('core', 'Thumbnail'),
            'instructions' => "&nbsp;",
            'id' => "$id-thumbnail",
            'name' => "$this->handle[thumbnail]",
            'elements' => Asset::find()->id($value)->all(),
            'limit' => 1,
            'elementType' => 'craft\\elements\\Asset',
            'required' => true,
            'field' => $this,
        ]);
    }
}
