<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ListItemsFixture extends TestFixture
{

    public $import = ['table' => 'list_items'];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
