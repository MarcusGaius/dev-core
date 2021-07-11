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

    private $_parts;

    public $record;

    protected $partMap;

    protected $element;

    private $prefix = 'developion_field_';

	// Constructor
	// =========================================================================

	public function __construct($partMap, $config, ElementInterface $element = null)
	{
        $this->partMap = $partMap;
        $this->element = $element;
        if (empty($config)) {
            $this->record = new FieldConfigurationRecord();
            $this->record->config = $this->_getParts();
            $this->record->save();
        } else {
            $this->record = FieldConfigurationRecord::find()->where(['id' => $config])->one();
        }
    }

	private function _getParts ()
	{
        foreach ($this->partMap as &$part) {
        }
        return Json::encode($this->partMap);
    }

	private function _render ($template, $variables)
	{

    }

    public function getValues(): iterable
    {
        $this->partMap[0]['value'] = '';
        $return = [
            'text' => $this->partMap[0]
        ];
        // $parts = $this->_parts();

        return $return;
    }

    public function getSearchableParts(): string
    {
        return '';
    }

}
