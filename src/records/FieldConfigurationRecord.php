<?php

namespace Developion\Core\records;

use craft\db\ActiveRecord;

/**
 * Class RichTextRecord
 *
 * @property int $id
 * @property string $config
 * @property int|null $siteId
 *
 * @package Developion\Core\records
 */
class FieldConfigurationRecord extends ActiveRecord
{

	// Props
	// =========================================================================

	// Props: Public Static
	// -------------------------------------------------------------------------

	/** @var string */
	public static $tableName = '{{%developion_field_config%}}';

	// Public Methods
	// =========================================================================

	// Public Methods: Static
	// -------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public static function tableName (): string
	{
		return self::$tableName;
	}

}