<?php

use Migrations\AbstractMigration;

class CreateItems extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('items');

        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('user_id', 'users', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);

        $table->addIndex(['user_id', 'name'], [
            'name' => 'idx_items_user_id_name',
            'unique' => true
        ]);

        $table->create();
    }
}
