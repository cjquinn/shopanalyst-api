<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ListsFixture extends TestFixture
{

    public $import = ['table' => 'lists'];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
