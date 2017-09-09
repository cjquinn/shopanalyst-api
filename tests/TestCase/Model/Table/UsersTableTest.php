<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class UsersTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * @var array
     */
    public $fixtures = ['app.users'];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Users = TableRegistry::get('Users');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        $user = $this->Users->newEntity();
        $data = [];

        $this->Users->patchEntityAdd($user, $data);

        $expected = [
            'email' => [
                '_required' => 'This field is required'
            ],
            'password' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->errors());
    }

    /**
     * @return void
     */
    public function testPatchEntityEdit()
    {
        $user = $this->Users->get(1);
        $data = [];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'email' => [
                '_required' => 'This field is required'
            ],
            'current_password' => [
                '_required' => 'This field is required'
            ],
            'new_password' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->errors());

        $user = $this->Users->get(1);
        $data = [
            'email' => 'christy@myshopanalyst.com',
            'current_password' => 'password',
            'new_password' => ''
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'new_password' => [
                '_empty' => 'You must enter a new password'
            ]
        ];

        $this->assertEquals($expected, $user->errors());

        $user = $this->Users->get(1);
        $data = [
            'email' => 'christy@myshopanalyst.com',
            'current_password' => '',
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'current_password' => [
                '_empty' => 'You must enter your current password'
            ]
        ];

        $this->assertEquals($expected, $user->errors());

        $user = $this->Users->get(1);
        $data = [
            'email' => 'christy@myshopanalyst.com',
            'current_password' => '',
            'new_password' => ''
        ];

        $this->Users->patchEntityEdit($user, $data);

        $this->assertEmpty($user->errors());

        $user = $this->Users->get(1);
        $data = [
            'email' => 'christy@myshopanalyst.com',
            'current_password' => 'notmypassword',
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'current_password' => [
                'match' => 'The password you entered was incorrect'
            ]
        ];

        $this->assertEquals($expected, $user->errors());
        $this->assertFalse($user->dirty('password'));

        $user = $this->Users->get(1);
        $data = [
            'email' => 'christy@myshopanalyst.com',
            'current_password' => 'password',
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $this->assertEmpty($user->errors());
        $this->assertTrue($user->dirty('password'));
    }

    /**
     * @return void
     */
    public function testPatchEntitySetToken()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntitySetToken($user);

        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);
    }

    /**
     * @return void
     */
    public function testPatchEntityClearToken()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntitySetToken($user);

        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);

        $this->Users->patchEntityClearToken($user);

        $this->assertNull($user->token);
        $this->assertNull($user->token_sent);
    }

    /**
     * @return void
     */
    public function testPatchEntityResetPassword()
    {
        $user = $this->Users->newEntity();
        $data = [];

        $this->Users->patchEntitySetToken($user);
        $this->Users->patchEntityResetPassword($user, $data);

        $expected = [
            'password' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->errors());
        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);

        $data = [
            'password' => 'password'
        ];

        $this->Users->patchEntityResetPassword($user, $data);

        $this->assertEmpty($user->errors());
        $this->assertNull($user->token);
        $this->assertNull($user->token_sent);
    }

    /**
     * @return void
     */
    public function testSaveResetPassword()
    {
        // token is dirty and is_activated
        $user = $this->Users->get(1);

        $this->Users->patchEntitySetToken($user);

        $usersTableMock = $this->getMockForModel(
            'App\Model\Table\UsersTable',
            ['getMailer'],
            ['alias' => 'UsersTable', 'table' => 'users']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\UserMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['resetPassword'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('resetPassword');

        $usersTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $usersTableMock->save($user);
    }
}
