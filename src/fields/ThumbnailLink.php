<?php

namespace Developion\Core\fields;

use Craft;
use craft\base\EagerLoadingFieldInterface;
use craft\base\ElementInterface;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\Json;
use Developion\Core\traits\HasThumbnail;
// use Developion\Core\assets\cp\CoreCp;
use yii\db\Schema;

class ThumbnailLink extends BaseLink
{
    use HasThumbnail;

    public $title;

    public $text;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', "Thumbnail Link\n");
    }

	public function getSearchKeywords ($value, ElementInterface $element): string {
		return $value->title . ' ' . $value->text;
	}

	public function normalizeValue ($value, ElementInterface $element = null)
	{
        // Craft::dd($value);
        return $value;
	}

    protected function inputHtml($value, ElementInterface $element = null): string
    {
        // Craft::dd($this->thumbnail);
        $view = Craft::$app->getView();
        // $view->registerAssetBundle(CoreCp::class);

        $values = is_iterable($value) ? $value : Json::decode($value);

        $id = Html::id($this->handle);

        return Html::tag(
            'div',
            $this->getThumbnailHtml($values['thumbnail'] ?? $this->thumbnail, $id)
            .
            Cp::fieldHtml('template:_includes/forms/lightswitch', [
                'label' => Craft::t('core', 'Target'),
                'instructions' => Craft::t('core', 'Whether the link opens in a new tab.'),
                'id' => "$id-target",
                'name' => "$this->handle[target]",
                'on' => $values['target'] ?? $this->target,
                'field' => $this,
            ])
            .
            Cp::fieldHtml('template:_includes/forms/select', [
                'label' => Craft::t('core', 'Link Type'),
                'instructions' => "&nbsp;",
                'id' => "$id-link-type",
                'name' => "$this->handle[linkType]",
                'options' => $this->linkTypes,
                'value' => $values['linkType'] ?? $this->linkType,
                'required' => true,
                'field' => $this,
            ])
            .
            Cp::fieldHtml('template:_includes/forms/text', [
                'label' => 'Link Button Text',
                'instructions' => "&nbsp;",
                'id' => "$id-button-text",
                'name' => "$this->handle[buttonText]",
                'value' => Craft::t('core', $values['buttonText'] ?? $this->buttonText),
                'required' => true,
                'field' => $this,
            ])
            .
            Cp::fieldHtml('template:_includes/forms/text', [
                'label' => 'Link Button URL',
                'id' => "$id-button-link",
                'name' => "$this->handle[buttonLink]",
                'value' => $values['buttonLink'] ?? $this->buttonLink,
                'type'  => 'url',
                'required' => true,
                'field' => $this,
            ])
            .
            Cp::fieldHtml('template:_includes/forms/text', [
                'label' => 'Title',
                'id' => "$id-title",
                'name' => "$this->handle[title]",
                'value' => $values['title'] ?? $this->title,
                'required' => true,
                'field' => $this,
                'class' => 'half-width'
            ])
            .
            Cp::fieldHtml('template:_includes/forms/textarea', [
                'label' => 'Text',
                'id' => "$id-text",
                'name' => "$this->handle[text]",
                'value' => $values['text'] ?? $this->text,
                'rows' => 4,
                'required' => true,
                'field' => $this,
                'class' => 'full-width'
            ]),
            [
                'class' => ['flex', 'flex-wrap', 'icon-link']
            ]
        );
    }
}
