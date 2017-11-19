<?php

namespace App\Controller;

use Cake\Event\Event;

class UsersController extends AppController
{

    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow([
            'add',
            'login',
            'requestPasswordReset',
            'resetPassword'
        ]);
    }

    /**
     * @return void
     */
    public function add()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntityAdd($user, $this->request->getData());

        if (!$this->Users->save($user)) {
            $this->response = $this->response->withStatus(400);
        }

        if ($user->id) {
            $this->set('jwt', $this->Users->generateJwt($user->id));
        }

        $this->set([
            'user' => $user,
            'errors' => $user->errors()
        ]);
    }

    /**
     * @return void
     */
    public function currentUser()
    {
        $this->set('user', $this->Auth->user());
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit()
    {
        $user = $this->Users->get($this->Auth->user('id'));

        $this->Users->patchEntityEdit($user, $this->request->getData());

        if (!$this->Users->save($user)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'user' => $user,
            'errors' => $user->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Network\Exception\ForbiddenException
     */
    public function login()
    {
        $user = $this->Auth->identify();

        if (!$user) {
            $this->set('errors', [
                '_error' => [
                    'invalid' => 'Invalid email or password, please try again'
                ]
            ]);

            $this->response = $this->response->withStatus(400);
        }

        if ($user) {
            $this->set([
                'jwt' => $this->Users->generateJwt($user['id']),
                'user' => $user
            ]);
        }
    }

    /**
     * @return void
     */
    public function requestPasswordReset()
    {
        $user = $this->Users
            ->findByEmail($this->request->getData('email'))
            ->first();

        if (!$user) {
            $this->set('errors', [
                'email' => [
                    'invalid' => 'Invalid email, please try again'
                ]
            ]);

            $this->response = $this->response->withStatus(400);
        }

        if ($user) {
            $this->Users->patchEntitySetToken($user);

            $this->Users->save($user);
        }
    }

    /**
     * @return void
     * @throws \Cake\Network\Exception\ForbiddenException|
     *         \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function resetPassword()
    {
        $user = $this->Users->getByToken($this->request->getQuery('token'));

        if ($this->request->is('patch')) {
            $this->Users->patchEntityResetPassword($user, $this->request->getData());

            if (!$this->Users->save($user)) {
                $this->response = $this->response->withStatus(400);
            }

            $this->set([
                'user' => $user,
                'errors' => $user->errors()
            ]);
        }
    }
}
