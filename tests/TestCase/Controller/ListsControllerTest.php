<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class ListsControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'get' => '/lists.json',
            'post' => '/lists.json',
            'get' => '/lists/1.json',
            'put' => '/lists/1.json',
            'delete' => '/lists/1.json',
            'patch' => '/lists/1/add-items.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        $this->_testAuthorised([
            'put' => '/lists/2.json',
            'delete' => '/lists/2.json',
            'patch' => '/lists/2/add-items.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->post('/lists.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->post('/lists.json', ['name' => 'Weekly shop']);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testAddItemsBadData()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/add-items.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddItemsPatch()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->patch('/lists/1/add-items.json', [
            'list_items' => [
                ['item' => ['name' => 'Potatos']]
            ]
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
        $this->delete('/lists/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->put('/lists/1.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testEditPut()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->put('/lists/1.json', ['name' => 'Weekly shop']);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->get('/lists.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewInvalidUser()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->get('/lists/2.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->get('/lists/1.json');

        $this->assertResponseCode(200);
    }
}
