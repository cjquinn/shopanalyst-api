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
            'post' => '/lists/1/list-items.json',
            'delete' => '/lists/1/list-items/1.json',
            'patch' => '/lists/1/list-items/1/toggle-completed.json',
            'patch' => '/lists/1/list-items/1/update-quantity.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        $this->_testAuthorised([
            // Invalid owner
            'post' => '/lists/2/list-items.json',
            'delete' => '/lists/2/list-items/2.json',
            'patch' => '/lists/2/list-items/2/toggle-completed.json',
            'patch' => '/lists/2/list-items/2/update-quantity.json',
            // Invalid item
            'delete' => '/lists/1/list-items/2.json',
            'patch' => '/lists/1/list-items/2/toggle-completed.json',
            'patch' => '/lists/1/list-items/2/update-quantity.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->post('/lists/1/list-items.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->post('/lists/1/list-items.json', [
            'item' => ['name' => 'Potatos']
        ]);

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
    public function testToggleCompletedPatch()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/list-items/1/toggle-completed.json');

        $this->assertResponseCode(200);

        $this->assertTrue(
            $this->_table('ListItems')
                ->exists(['id' => 1, 'completed IS NOT' => null])
        );
    }

    /**
     * @return void
     */
    public function testUpdateQuantityBadData()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/list-items/1/update-quantity.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testUpdateQuantityPatch()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/list-items/1/update-quantity.json', [
            'quantity' => 1
        ]);

        $this->assertResponseCode(200);
    }
}
