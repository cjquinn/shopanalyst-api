<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ItemsFixture extends TestFixture
{

    public $import = ['table' => 'items'];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'Magners'
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'name' => 'Red wine'
            ]
        ];

        parent::init();
    }
}
