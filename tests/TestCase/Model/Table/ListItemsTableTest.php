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
    public function testValidationDefault()
    {
        // Required
        $errors = $this->ListItems->validator()->errors([]);

        $expected = [
            'item' => [
                '_required' => 'This field is required'
            ],
            'item_id' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Empty
        $errors = $this->ListItems->validator()->errors([
            'item_id' => ''
        ]);

        $expected = [
            'item_id' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Invalid
        $errors = $this->ListItems->validator()->errors([
            'item_id' => 'One'
        ]);

        $expected = [
            'item_id' => [
                'integer' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Nested
        $errors = $this->ListItems->validator()->errors([
            'item' => 'An item'
        ]);

        $expected = [
            'item' => [
                '_nested' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $errors);
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
    public function testPatchEntityUpdate()
    {
        $listItem = $this->ListItems->get(1);
        $data = [
            'quantity' => 10
        ];

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
