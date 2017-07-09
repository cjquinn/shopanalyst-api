<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ListsTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\ListsTable
     */
    public $Lists;

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

        $this->Lists = TableRegistry::get('Lists');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Lists);

        parent::tearDown();
    }
}
