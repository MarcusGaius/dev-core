<?php

namespace Developion\Core\records;

use yii\db\ActiveRecord;

/**
 * Class RichTextRecord
 *
 * @property int $id
 * @property string $text
 * @property int|null $siteId
 *
 * @package Developion\Core\records
 */
class RichTextRecord extends ActiveRecord
{

	// Props
	// =========================================================================

	// Props: Public Static
	// -------------------------------------------------------------------------

	/** @var string */
	public static $tableName = '{{%developion_field_rich_text%}}';

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