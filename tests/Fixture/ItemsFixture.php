<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ItemsFixture extends TestFixture
{

    public $import = ['table' => 'items'];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
