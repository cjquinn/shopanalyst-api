<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ListItemsFixture extends TestFixture
{

    public $import = ['table' => 'list_items'];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'item_id' => 1,
                'list_id' => 1,
                'quantity' => 1,
                'is_completed' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'item_id' => 2,
                'list_id' => 2,
                'quantity' => 1,
                'is_completed' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
