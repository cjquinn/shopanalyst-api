<?php

namespace App\Controller;

class ListsController extends AppController
{
    public $paginate = ['limit' => 10];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Paginator');
    }

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if ($this->request->getParam('id') &&
            !$this->Lists->isOwnedBy($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $list = $this->Lists->newEntity();

        $this->Lists->patchEntity($list, $this->request->getData());

        if (!$this->Lists->save($list)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'list' => $list,
            'errors' => $list->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function addItems($id)
    {
        $list = $this->Lists->get($id);

        $this->Lists->patchEntityAddItems($list, $this->request->getData());

        if (!$this->Lists->save($list)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'list' => $list,
            'errors' => $list->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $list = $this->Lists->get($id);

        $this->Lists->softDelete($list);

        $this->set('list', $list);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($id)
    {
        $list = $this->Lists->get($id);

        $this->Lists->patchEntity($list, $this->request->getData());

        if (!$this->Lists->save($list)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'list' => $list,
            'errors' => $list->errors()
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        $lists = $this->Lists
            ->findByUserId($this->Auth->user('id'))
            ->find('populated');

        $this->set('lists', $this->paginate($lists));
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $list = $this->Lists->get($id, ['finder' => 'populated']);

        $this->set('list', $list);
    }
}
