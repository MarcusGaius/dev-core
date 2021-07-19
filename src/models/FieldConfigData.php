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
use craft\base\Field;
use craft\base\FieldInterface;
use craft\db\Query;
use craft\elements\Entry;
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use craft\records\Entry as EntryRecord;
use Developion\Core\fields\TestField;
use Developion\Core\records\FieldConfigurationRecord;
use yii\base\BaseObject;
use yii\base\Behavior;

class FieldConfigData extends BaseObject
{
    const RICHTEXT = 'craft\redactor\Field';

    protected FieldConfigurationRecord|null $record;

    protected $field;

    protected Entry $element;

    // Constructor
    // =========================================================================

    public function __construct(TestField $field, ElementInterface $element = null)
    {
        $this->field = $field;
        $this->element = $element;
        $pluginHandle = $this->field->handle;
        // $entry = EntryRecord::findOne($this->element->getId());
        // Craft::dd();
        $this->record = FieldConfigurationRecord::find()
            ->where([
                'id' => $this->element->getBehavior('customFields')->$pluginHandle
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
            // $pluginHandle = $this->field->handle;
            $fieldData = $this->element->getBehavior('customFields');
            // $part['value'] = $fieldData->$pluginHandle[$part['handle']];
            // return $part;
            // $fieldData = Craft::$app->request->getBodyParam('fields');
            // if (!$fieldData) {
            //     $fieldData = [$this->field->handle => Json::decode($this->record->config)];
            // }
            $array = false;
            if( is_numeric($fieldData->{$this->field->handle}) ) {
                $array = true;
                $this->record = FieldConfigurationRecord::findOne($fieldData->{$this->field->handle});
                Craft::dd($fieldData->{$this->field->handle} . ' ' . $this->element->getFieldValue($this->field->handle));
                $fieldData = Craft::$app->request->getBodyParam('fields');
            }
            
            $part['value'] = $array ? 
                $fieldData[$this->field->handle][$part['handle']] :
                $fieldData->{$this->field->handle}[$part['handle']];
            return $part;
        }, $this->field->getPartMap());

        $this->record->config = Json::encode($config, true);
        $this->record->save();
        $this->element->setFieldValue($this->field->handle, $this->record->id);
    }

    public function setOwnerId()
    {
        $this->record = FieldConfigurationRecord::findOne(
            $this->element->getFieldValue($this->field->handle)
        );
        $this->record->ownerId = $this->element->getCanonicalId();
        $this->record->save();
    }
}
