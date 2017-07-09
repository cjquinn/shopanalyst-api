<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class ItemsControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised(['get' => '/items.json']);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->get('/items.json');

        $this->assertResponseCode(200);
    }
}
