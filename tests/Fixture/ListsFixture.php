<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ListsFixture extends TestFixture
{

    public $import = ['table' => 'lists'];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'Christy\'s List',
                'is_deleted' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'name' => 'Lisa\'s List',
                'is_deleted' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
