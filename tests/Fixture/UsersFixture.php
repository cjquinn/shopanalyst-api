<?php

namespace App\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{

    public $import = [
        'table' => 'users'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Christy Quinn',
                'email' => 'christy@myshopanalyst.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Lisa Bogue',
                'email' => 'lisa@myshopanalyst.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
