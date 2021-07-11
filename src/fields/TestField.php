<?php

namespace Developion\Core\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\db\mysql\Schema;
use craft\helpers\ArrayHelper;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use Developion\Core\models\FieldConfigData;

class TestField extends Field
{
    public $config;

    private $partMap = [
        [
            'template'      => 'textarea',
            'name'          => 'Test Rich Text',
            'handle'        => 'testRichText',
            'type'          => FieldConfigData::RICHTEXT,
            'searchable'    => true
        ]
    ];

    protected function partData($partMap){
        return array_map(function($part) {
            return [
                'template'      => "template:_includes/forms/{$part['template']}",
                'name'          => $part['name'],
                'handle'        => $part['handle'],
                'type'          => $part['type'],
                'searchable'    => $part['searchable'] ?? '',
                'instructions'  => $part['instructions'] ?? '',
            ];
        }, $partMap);
        
    }
    
	public function normalizeValue($value, ElementInterface $element = null)
	{
        if ($value instanceof FieldConfigData)
        return $value;
        
		// Craft::dd($value);
		if (is_string($value))
			$value = Json::decodeIfJson($value);

		return new FieldConfigData($this->partData($this->partMap), $this->config, $element);
	}

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', "Test Field\n");
    }

	public function getContentColumnType (): string
	{
		return Schema::TYPE_JSON;
	}

	public function getSearchKeywords ($value, ElementInterface $element): string {
		return $value->getSearchableParts();
	}

    protected function inputHtml($value, ElementInterface $element = null): string
    {
        $id = Html::id($this->handle);

        $html = '';

        foreach ($value->getValues() as $part) {
            $html .= Cp::fieldHtml($part['template'], [
                'label' => $part['name'],
                'handle' => $part['handle'],
                'id' => "$id-{$part['handle']}",
                'name' => "$this->handle[{$part['handle']}]",
                'value' => $part['value'],
                'rows' => 4,
                'required' => true,
                'field' => $this,
                'class' => 'full-width'
            ]);
        }

        return Html::tag(
            'div',
            $html,
            [
                'class' => ['flex', 'flex-wrap', 'icon-link']
            ]
        );
    }
}