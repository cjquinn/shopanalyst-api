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
        'app.items',
        'app.list_items',
        'app.lists'
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
    public function testValidationAdd()
    {
        // Required
        $listItem = $this->ListItems->newEntity();
        $data = [];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $expected = [
            'item' => [
                '_required' => 'This field is required'
            ],
            'item_id' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Empty
        $listItem = $this->ListItems->newEntity();
        $data = ['item_id' => ''];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $expected = [
            'item_id' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Invalid
        $listItem = $this->ListItems->newEntity();
        $data = ['item_id' => 'One'];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $expected = [
            'item_id' => [
                'integer' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Nested
        $listItem = $this->ListItems->newEntity();
        $data = ['item' => 'An item'];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $expected = [
            'item' => [
                '_nested' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Invalid item_id
        $listItem = $this->ListItems->newEntity();
        $data = ['item_id' => 2];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $expected = [
            'item_id' => [
                'invalid' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        $listItem = $this->ListItems->newEntity();
        $data = [
            'item' => ['name' => 'Waffles']
        ];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $this->assertEmpty($listItem->getErrors());
        $this->assertEquals(1, $listItem->list_id);
        $this->assertNotNull($listItem->item);
        $this->assertEquals(1, $listItem->item->user_id);
        $this->assertEquals('Waffles', $listItem->item->name);

        $listItem = $this->ListItems->newEntity();
        $data = ['item_id' => 1];

        $this->ListItems->patchEntityAdd($listItem, $data, 1, 1);

        $this->assertEmpty($listItem->getErrors());
        $this->assertEquals(1, $listItem->item_id);
    }

    /**
     * @return void
     */
    public function testValidationUpdateQuantity()
    {
        // Required
        $listItem = $this->ListItems->get(1);
        $data = [];

        $this->ListItems->patchEntityUpdateQuantity($listItem, $data);

        $expected = [
            'quantity' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Empty
        $listItem = $this->ListItems->get(1);
        $data = [
            'quantity' => ''
        ];

        $this->ListItems->patchEntityUpdateQuantity($listItem, $data);

        $expected = [
            'quantity' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Natural Number
        $listItem = $this->ListItems->get(1);
        $data = [
            'quantity' => 0
        ];

        $this->ListItems->patchEntityUpdateQuantity($listItem, $data);

        $expected = [
            'quantity' => [
                'naturalNumber' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $listItem->getErrors());

        // Natural Number
        $listItem = $this->ListItems->get(1);
        $data = [
            'quantity' => 1
        ];

        $this->ListItems->patchEntityUpdateQuantity($listItem, $data);

        $expected = [];

        $this->assertEquals($expected, $listItem->getErrors());
    }

    /**
     * @return void
     */
    public function testPatchEntityUpdateQuantity()
    {
        $listItem = $this->ListItems->get(1);
        $data = ['quantity' => 10];

        $this->ListItems->patchEntityUpdateQuantity($listItem, $data);

        $this->assertEquals(10, $listItem->quantity);
    }

    /**
     * @return void
     */
    public function testToggleCompleted()
    {
        $listItem = $this->ListItems->get(1);

        $this->assertNull($listItem->completed);

        $this->ListItems->toggleCompleted($listItem);

        $this->assertNotNull($listItem->completed);

        $this->ListItems->toggleCompleted($listItem);

        $this->assertNull($listItem->completed);
    }
}
