<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ListItemsTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\ListItemsTable
     */
    public $ListItems;

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

        $this->ListItems = TableRegistry::get('ListItems');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->ListItems);

        parent::tearDown();
    }
}
