<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ItemsTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\ItemsTable
     */
    public $Items;

    /**
     * @var array
     */
    public $fixtures = [];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Items = TableRegistry::get('Items');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Items);

        parent::tearDown();
    }
}
