<?php

use Migrations\AbstractMigration;

class CreateListItems extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('list_items');

        $table->addColumn('item_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('item_id', 'items', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('list_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('list_id', 'lists', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addIndex(['item_id', 'list_id'], [
            'name' => 'idx_list_items_item_id_list_id',
            'unique' => true
        ]);

        $table->addColumn('quantity', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false
        ]);

        $table->addColumn('is_completed', 'boolean', [
            'default' => false,
            'null' => false
        ]);

        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->create();
    }
}
