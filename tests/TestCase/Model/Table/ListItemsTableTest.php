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
    public $fixtures = [
        'app.list_items'
    ];

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

    /**
     * @return void
     */
    public function testValidationDefault()
    {
        $errors = $this->ListItems->validator()->errors([]);

        $expected = [
            'item' => [
                '_required' => 'This field is required'
            ],
            'item_id' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($errors, $expected);

        $errors = $this->ListItems->validator()->errors([
            'item_id' => 'One'
        ]);

        $expected = [
            'item_id' => [
                'integer' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($errors, $expected);

        $errors = $this->ListItems->validator()->errors([
            'item' => 'An item'
        ]);

        $expected = [
            'item' => [
                '_nested' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($errors, $expected);
    }

    /**
     * @return void
     */
    public function testUpdateQuantity()
    {
        $listItem = $this->ListItems->get(1);

        $this->assertEquals(1, $listItem->quantity);

        $this->ListItems->updateQuantity($listItem, 1);

        $this->assertEquals(2, $listItem->quantity);

        $this->ListItems->updateQuantity($listItem, -1);

        $this->assertEquals(1, $listItem->quantity);

        $this->ListItems->updateQuantity($listItem, -1);

        $this->assertEquals(1, $listItem->quantity);
    }

    /**
     * @return void
     */
    public function testToggleCompleted()
    {
        $listItem = $this->ListItems->get(1);

        $this->assertFalse($listItem->is_completed);

        $this->ListItems->toggleCompleted($listItem);

        $this->assertTrue($listItem->is_completed);

        $this->ListItems->toggleCompleted($listItem);

        $this->assertFalse($listItem->is_completed);
    }
}
