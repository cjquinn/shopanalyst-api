<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class ListItemsControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'delete' => '/lists/1/list-items/1.json',
            'patch' => '/lists/1/list-items/1/decrease-quantity.json',
            'patch' => '/lists/1/list-items/1/increase-quantity.json',
            'patch' => '/lists/1/list-items/1/toggle-completed.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        $this->_testAuthorised([
            // Invalid owner
            'delete' => '/lists/2/list-items/2.json',
            'patch' => '/lists/2/list-items/2/decrease-quantity.json',
            'patch' => '/lists/2/list-items/2/increase-quantity.json',
            'patch' => '/lists/2/list-items/2/toggle-completed.json',
            // Invalid item
            'delete' => '/lists/1/list-items/2.json',
            'patch' => '/lists/1/list-items/2/decrease-quantity.json',
            'patch' => '/lists/1/list-items/2/increase-quantity.json',
            'patch' => '/lists/1/list-items/2/toggle-completed.json'
        ]);
    }

    /**
     * @return void
     */
    public function testDecreaseQuantityPatch()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/list-items/1/decrease-quantity.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->delete('/lists/1/list-items/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIncreaseQuantityPatch()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/list-items/1/increase-quantity.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testToggleCompletedPatch()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/list-items/1/toggle-completed.json');

        $this->assertResponseCode(200);
    }
}
