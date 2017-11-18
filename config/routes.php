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
                ],
                'duplicate' => [
                    'action' => 'duplicate',
                    'method' => 'POST',
                    'path' => '/:id/duplicate'
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
                    'delete',
                    'toggleCompleted',
                    'updateQuantity'
                ],
                'map' => [
                    'toggleCompleted' => [
                        'action' => 'toggleCompleted',
                        'method' => 'PATCH',
                        'path' => '/:id/toggle-completed'
                    ],
                    'updateQuantity' => [
                        'action' => 'updateQuantity',
                        'method' => 'PATCH',
                        'path' => '/:id/update-quantity'
                    ],
                ]
            ]);
        }
    );

    /**
     * Users
     */
    $routes->resources('Users', [
        'only' => ['create', 'settings'],
        'map' => [
            'settings' => [
                'action' => 'edit',
                'method' => 'PATCH'
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
