<?php

namespace App\Error;

use Cake\Error\ExceptionRenderer;

class AppExceptionRenderer extends ExceptionRenderer
{
    /**
     * @return \Cake\Http\Response
     */
    public function render()
    {
        $this->controller->response = $this->controller->response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Max-Age', '86400');

        return parent::render();
    }
}
