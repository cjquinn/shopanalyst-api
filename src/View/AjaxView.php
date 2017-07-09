<?php

namespace App\View;

use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;

class AjaxView extends AppView
{

    /**
     * @var string
     */
    public $layout = 'ajax';

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->response->type('ajax');
    }
}
