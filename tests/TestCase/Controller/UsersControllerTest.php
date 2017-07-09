<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class UsersControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'get' => '/auth/current-user.json',
            'put' => '/users/account.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAccountBadData()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->put('/users/account.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAccountPut()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->put('/users/account.json', [
            'name' => 'Christy Quinn',
            'email' => 'christy@myshopanalyst.com',
            'current_password' => '',
            'new_password' => ''
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->post('/users.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->post('/users.json', [
            'name' => 'Christy Quinn',
            'email' => 'christyjquinn@gmail.com',
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testCurrentUserGet()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();
        $this->get('/auth/current-user.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testLoginBadData()
    {
        $this->_setAjaxRequest();
        $this->post('/auth/login.json', [
            'email' => 'christy@myshopanalyst.com',
            'password' => 'notmypassword'
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testLoginPost()
    {
        $this->_setAjaxRequest();
        $this->post('/auth/login.json', [
            'email' => 'christy@myshopanalyst.com',
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetBadData()
    {
        $this->_setAjaxRequest();
        $this->post('/auth/request-password-reset.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetPost()
    {
        $this->_setAjaxRequest();
        $this->post('/auth/request-password-reset.json', [
            'email' => 'christy@myshopanalyst.com'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testResetPasswordNoToken()
    {
        $this->_setAjaxRequest();
        $this->get('/auth/reset-password.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testResetPasswordExpiredToken()
    {
        $token = $this->_getToken(true);

        $this->_setAjaxRequest();
        $this->get('/auth/reset-password.json?token=' . $token);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testResetPasswordBadData()
    {
        $token = $this->_getToken();

        $this->_setAjaxRequest();
        $this->patch('/auth/reset-password.json?token=' . $token);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testResetPasswordPatch()
    {
        $token = $this->_getToken();

        $this->_setAjaxRequest();
        $this->patch('/auth/reset-password.json?token=' . $token, [
            'password' => 'newpassword'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function _getToken($isExpired = false)
    {
        $table = $this->_table('Users');

        $user = $table->get(1);
        $table->patchEntitySetToken($user);

        if ($isExpired) {
            $user->token_sent->modify('-3 hours');
        }

        $table->save($user);

        return $user->token;
    }
}
