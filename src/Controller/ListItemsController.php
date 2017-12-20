<?php

namespace App\Controller;

class ListItemsController extends AppController
{
    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if (!$this->ListItems->Lists->isOwnedBy($this->request->getParam('list_id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->getParam('id') &&
            !$this->ListItems->isOwnedBy($this->request->getParam('id'), $this->request->getParam('list_id'))) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $listItem = $this->ListItems->newEntity();

        $this->ListItems->patchEntityAdd(
            $listItem,
            $this->request->getData(),
            $this->Auth->user('id'),
            $this->request->getParam('list_id')
        );

        if (!$this->ListItems->save($listItem)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'listItem' => $listItem,
            'errors' => $listItem->getErrors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $listItem = $this->ListItems->get($id);

        $this->ListItems->delete($listItem);

        $this->set('listItem', $listItem);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function toggleCompleted($id)
    {
        $listItem = $this->ListItems->get($id);

        $this->ListItems->toggleCompleted($listItem);

        $this->set('listItem', $listItem);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function updateQuantity($id)
    {
        $listItem = $this->ListItems->get($id);

        $this->ListItems->patchEntityUpdateQuantity($listItem, $this->request->getData());

        if (!$this->ListItems->save($listItem)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'listItem' => $listItem,
            'errors' => $listItem->getErrors()
        ]);
    }
}
