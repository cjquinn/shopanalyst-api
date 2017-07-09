<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

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
    public function account()
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
     */
    public function add()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntityAdd($user, $this->request->getData());

        if (!$this->Users->save($user)) {
            $this->response = $this->response->withStatus(400);
        } else {
            $this->set('jwt', $this->Users->generateJwt($user['id']));
        }

        $this->set([
            'user' => $user,
            'errors' => $user->errors()
        ]);
    }

    /**
     * @return void
     */
    public function login()
    {
        $user = $this->Auth->identify();

        if (!$user) {
            if ($this->request->hasHeader('authorization')) {
                throw new ForbiddenException();
            }

            $this->response = $this->response->withStatus(400);
        } elseif (!$this->request->hasHeader('authorization')) {
            $this->set('jwt', $this->Users->generateJwt($user['id']));
        }

        $this->set('user', $user);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function requestPasswordReset()
    {
        $user = $this->Users
            ->findByEmail($this->request->getData('email'))
            ->first();

        if (!$user) {
            $this->response = $this->response->withStatus(400);
        } else {
            $this->Users->patchEntitySetToken($user);

            $this->Users->save($user);
        }
    }

    /**
     * @return void
     * @throws \Cake\Network\Exception\ForbiddenException
     */
    public function resetPassword()
    {
        $user = $this->Users->getByToken($this->request->getQuery('token'));


        if (!$user->token_sent->wasWithinLast('1 Hour')) {
            throw new ForbiddenException();
        }

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
