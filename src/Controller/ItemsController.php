<?php

namespace App\Controller;

class ItemsController extends AppController
{
    /**
     * @return void
     */
    public function index()
    {
        $items = $this->Items
            ->findByUserId($this->Auth->user('id'))
            ->find('filtered', $this->request->getQueryParams());

        $this->set('items', $items);
    }
}
