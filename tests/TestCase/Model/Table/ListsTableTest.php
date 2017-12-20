<?php

namespace App\Test\TestCase\Model\Table;

use Cake\Collection\Collection;
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
    public $fixtures = [
        'app.items',
        'app.list_items',
        'app.lists',
        'app.users'
    ];

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

    /**
     * @return void
     */
    public function testInitialization()
    {
        $this->assertTrue($this->Lists->hasBehavior('Deletable'));
    }

    /**
     * @return void
     */
    public function testDuplicate()
    {
        $list = $this->Lists->get(1, [
            'finder' => 'populated'
        ]);
        $duplicateList = $this->Lists->duplicate($list->id);

        $this->assertEquals($list->user_id, $duplicateList->user_id);
        $this->assertEquals($list->name, $duplicateList->name);

        $listItemsCollection = new Collection($list->list_items);
        $duplicateListItemsCollection = new Collection($duplicateList->list_items);

        $this->assertEquals(
            $listItemsCollection->extract('item_id')->toArray(),
            $duplicateListItemsCollection->extract('item_id')->toArray()
        );
    }
}
