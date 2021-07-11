<?php

namespace Developion\Core\migrations;

use craft\db\Migration;
use Developion\Core\records\FieldConfigurationRecord;
use Developion\Core\records\RichTextRecord as RichText;

class Install extends Migration
{

    public function safeUp()
    {
        $this->createTables();
        $this->addForeignKeys();
    }

    public function safeDown()
    {
		$this->dropTableIfExists(RichText::$tableName);
		$this->dropTableIfExists(FieldConfigurationRecord::$tableName);
    }

    public function createTables()
    {
        // Rich Text
        // ---------------------------------------------------------------------
        $this->createTable(
            RichText::$tableName,
            [
                'id' => $this->primaryKey(),
                'ownerId' => $this->integer(),
                'text'   => $this->text()->notNull(),
                'siteId' => $this->integer()->null(),
            ]
        );
        
        $this->createTable(FieldConfigurationRecord::$tableName, [
            'id' => $this->primaryKey(),
            'config' => $this->json()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);
    }

    /**
     * Adds the foreign keys.
     */
    public function addForeignKeys()
    {
        $this->addForeignKey(null, RichText::$tableName, ['ownerId'], FieldConfigurationRecord::$tableName, ['id'], 'CASCADE', null);
    }
}
