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
    public function testValidationDefault()
    {
        $errors = $this->Items->validator()->errors([]);

        $expected = [
            'name' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($errors, $expected);

        $errors = $this->Items->validator()->errors([
            'name' => ''
        ]);

        $expected = [
            'name' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($errors, $expected);
    }
}
