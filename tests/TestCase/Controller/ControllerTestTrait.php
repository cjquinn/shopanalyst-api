<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;

trait ControllerTestTrait
{

    public $fixtures = [
        'app.items',
        'app.list_items',
        'app.lists',
        'app.users'
    ];

    /**
     * @return void
     */
    private function _testUnauthorised($routes)
    {
        foreach ($routes as $method => $path) {
            $this->enableCsrfToken();
            $this->{$method}($path);

            $redirect = ['_name' => 'login'];

            if ($method === 'get') {
                $redirect['?'] = ['redirect' => $path];
            }

            $this->assertResponseCode(403);
        }
    }

    /**
     * @return void
     */
    private function _testInvalidOwner($routes)
    {
        $this->_setAuthSession(1);

        foreach ($routes as $method => $path) {
            $this->enableCsrfToken();
            $this->{$method}($path);

            $this->assertResponseCode(403);
        }
    }

    /**
     * Sets session up for Auth component
     *
     * @param $id The id of the login
     * @return void
     */
    private function _setAuthSession($id)
    {
        $token = $this->_table('Users')->generateJwt($id);

        if (!isset($this->_request['headers'])) {
            $this->_request['headers'] = [];
        }

        $this->_request['headers']['Authorization'] = 'Bearer ' . $token;
    }

    /**
     * @return void
     */
    private function _setAjaxRequest()
    {
        $_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * @return \Cake\ORM\Table
     */
    private function _table($table)
    {
        return TableRegistry::get($table);
    }
}
