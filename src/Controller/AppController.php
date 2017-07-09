<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth', [
            'authenticate' => [
                'ADmad/JwtAuth.Jwt' => [
                    'fields' => ['username' => 'id'],
                    'parameter' => 'jwt',
                    'queryDatasource' => true
                ],
                'Form' => [
                    'fields' => ['username' => 'email']
                ]
            ],
            'authorize' => 'Controller',
            'loginAction' => false,
            'storage' => 'Memory',
            'unauthorizedRedirect' => false
        ]);
        $this->loadComponent('RequestHandler');
    }

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        return true;
    }

    /**
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->set('_serialize', true);
    }
}
