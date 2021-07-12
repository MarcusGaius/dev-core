<?php

/**
 * SEO for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2018 Ether Creative
 */

namespace Developion\Core\models;

use Craft;
use craft\base\ElementInterface;
use craft\base\FieldInterface;
use craft\db\Query;
use craft\helpers\Json;
use Developion\Core\fields\TestField;
use Developion\Core\records\FieldConfigurationRecord;
use yii\base\BaseObject;

class FieldConfigData extends BaseObject
{
    const RICHTEXT = 'craft\redactor\Field';

    protected $record;

    protected $field;

    protected $element;

    // Constructor
    // =========================================================================

    public function __construct(TestField $field, ElementInterface $element = null)
    {
        $this->field = $field;
        $this->element = $element;
        $this->record = FieldConfigurationRecord::find()
            ->where([
                'id' => $this->element->getFieldValue($this->field->handle)
            ])
            ->one();
        
    }

    private function _getParts()
    {
        foreach ($this->partMap as &$part) {
        }
        return Json::encode($this->partMap);
    }

    private function _render($template, $variables)
    {
    }

    public function getValues(): iterable
    {
        $data = Json::decodeIfJson($this->record?->config);
        if (!$data) {
            $data = $this->field->getPartMap();
            array_walk($data, function (&$part) {
                $part['value'] = '';
            });
        }
        return $data;
    }

    public function getSearchableParts(): string
    {
        return '';
    }

    public function setRecord()
    {
        if (!$this->record) {
            $this->record = new FieldConfigurationRecord();
        }

        $config = array_map(function ($part) {
            $fieldData = Craft::$app->request->getBodyParam('fields');
            $part['value'] = $fieldData ? $fieldData[$this->field->handle][$part['handle']] : '';
            return $part;
        }, $this->field->getPartMap());

        $this->record->config = Json::encode($config, true);
        $this->record->save();
        $this->element->setFieldValue($this->field->handle, $this->record->id);
        // Craft::dd($this->element->getFieldValue($this->field->handle));
    }
}
