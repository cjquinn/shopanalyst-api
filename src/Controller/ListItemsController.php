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

        if (!$this->ListItems->isOwnedBy($this->request->getParam('id'), $this->request->getParam('list_id'))) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function decreaseQuantity($id)
    {
        $listItem = $this->ListItems->get($id);

        $this->ListItems->decreaseQuantity($listItem);

        $this->set('listItem', $listItem);
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
    public function increaseQuantity($id)
    {
        $listItem = $this->ListItems->get($id);

        $this->ListItems->increaseQuantity($listItem);

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
}
