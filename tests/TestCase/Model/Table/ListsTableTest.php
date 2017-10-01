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
    public function testPatchEntityAddListItems()
    {
        $list = $this->Lists->get(1);
        $data = [];

        $this->Lists->patchEntityAddListItems($list, $data, 1);

        $expected = [
            'list_items' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $list->errors());

        $list = $this->Lists->get(1);
        $data = [
            'list_items' => 'Some list items'
        ];

        $this->Lists->patchEntityAddListItems($list, $data, 1);

        $expected = [
            'list_items' => [
                '_nested' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $list->errors());

        $list = $this->Lists->get(1);
        $data = [
            'list_items' => [
                ['item' => ['name' => 'Waffles']]
            ]
        ];

        $this->Lists->patchEntityAddListItems($list, $data, 1);

        $expected = [];

        $this->assertEquals($expected, $list->errors());
        $this->assertEquals(1, count($list->list_items));
        $this->assertNotNull($list->list_items[0]->item);
        $this->assertEquals(1, $list->list_items[0]->item->user_id);
        $this->assertEquals('Waffles', $list->list_items[0]->item->name);

        $list = $this->Lists->get(1);
        $data = [
            'list_items' => [
                ['item_id' => 1]
            ]
        ];

        $this->Lists->patchEntityAddListItems($list, $data, 1);

        $expected = [];

        $this->assertEquals($expected, $list->errors());
        $this->assertEquals(1, count($list->list_items));
        $this->assertEquals(1, $list->list_items[0]->item_id);
    }

    /**
     * @return void
     */
    public function testBeforeSave()
    {
        $list = $this->Lists->get(1);
        $data = [
            'list_items' => [
                ['item_id' => 2]
            ]
        ];

        $this->Lists->patchEntityAddListItems($list, $data, 1);

        $this->assertTrue($this->Lists->save($list) === false);
    }
}
