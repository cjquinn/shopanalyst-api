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
            $this->request->action !== 'view' &&
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

        $list->set('user_id', $this->Auth->user('id'));

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
    public function duplicate($id)
    {
        $list = $this->Lists->duplicate($id);

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
        $lists = $this->paginate(
            $this->Lists
                ->findByUserId($this->Auth->user('id'))
                ->find('populated')
        );

        $this->set([
            'lists' => $lists,
            'total' => $this->request->paging['Lists']['count'],
            '_serialize' => ['lists', 'total']
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $list = $this->Lists
            ->find('populated')
            ->where([
                $this->Lists->aliasField('id') => $id,
                $this->Lists->aliasField('user_id') => $this->Auth->user('id')
            ])
            ->firstOrFail();

        $this->set('list', $list);
    }
}
