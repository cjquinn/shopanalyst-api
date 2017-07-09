<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->extensions(['json']);

    /**
     * Items
     */
    $routes->resources('Items', ['only' => 'index']);

    /**
     * Lists
     */
    $routes->resources(
        'Lists',
        [
            'map' => [
                'addItems' => [
                    'action' => 'addItems',
                    'method' => 'PATCH',
                    'path' => '/:id/add-items'
                ]
            ]
        ],
        function (RouteBuilder $routes) {
            /**
             * ListItems
             */
            $routes->resources('ListItems', [
                'inflect' => 'dasherize',
                'only' => [
                    'decreaseQuantity',
                    'delete',
                    'increaseQuantity',
                    'toggleComplete'
                ],
                'map' => [
                    'decreaseQuantity' => [
                        'action' => 'decreaseQuantity',
                        'method' => 'PATCH',
                        'path' => '/:id/decrease-quantity'
                    ],
                    'increaseQuantity' => [
                        'action' => 'increaseQuantity',
                        'method' => 'PATCH',
                        'path' => '/:id/increase-quantity'
                    ],
                    'toggleComplete' => [
                        'action' => 'toggleComplete',
                        'method' => 'PATCH',
                        'path' => '/:id/toggle-complete'
                    ]
                ]
            ]);
        }
    );

    /**
     * Users
     */
    $routes->resources('Users', [
        'only' => ['account', 'create'],
        'map' => [
            'account' => [
                'action' => 'account',
                'method' => 'PUT'
            ]
        ]
    ]);

    /**
     * Auth
     */
    $routes->scope('/auth', function ($routes) {
        /**
         * Users
         */
        $routes->connect('/current-user', [
            'controller' => 'Users',
            'action' => 'currentUser',
            '_method' => 'GET'
        ]);

        $routes->connect('/login', [
            'controller' => 'Users',
            'action' => 'login',
            '_method' => 'POST'
        ]);

        $routes->connect('/request-password-reset', [
            'controller' => 'Users',
            'action' => 'requestPasswordReset',
            '_method' => 'POST'
        ]);

        $routes->connect('/reset-password', [
            'controller' => 'Users',
            'action' => 'resetPassword',
            '_method' => ['GET', 'PATCH']
        ]);
    });
});
