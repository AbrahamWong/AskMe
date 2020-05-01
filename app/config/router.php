<?php

$router = $di->getRouter();

// Define your routes here
$router->add('/signup',
    [
        'controller' => 'user',
        'action' => 'signup'
    ]);

$router->add('/signup/register',
    [
        'controller' => 'user',
        'action' => 'register'
    ]);

$router->add('/login',
    [
        'controller' => 'user',
        'action' => 'login'
    ]);

$router->add('/login/status',
    [
        'controller' => 'user',
        'action' => 'status'
    ]);

$router->add('/logout',
    [
        'controller' => 'user',
        'action' => 'logout'
    ]);

$router->add('/profile/{UID}',
    [
        'controller' => 'user',
        'action' => 'profile'
    ]);

$router->add('/profile/{UID}/edit',
    [
        'controller' => 'user',
        'action' => 'edit'
    ]);

$router->add('/question/create',
    [
        'controller' => 'question',
        'action' => 'create'
    ]
);

$router->add('/question/submit',
    [
        'controller' => 'question',
        'action' => 'submit'
    ]
);

$router->add('/question/read/{id}',
    [
        'controller' => 'question',
        'action' => 'read'
    ]
);

$router->add('/question/answer/{id}',
    [
        'controller' => 'question',
        'action' => 'answer'
    ]
);

$router->add('/question/best/{QId}/{AId}',
    [
        'controller' => 'question',
        'action' => 'best'
    ]
);

$router->add('/question/deleteAns/{QId}/{AId}',
    [
        'controller' => 'question',
        'action' => 'deleteAns'
    ]
);

$router->add('/search',
    [
        'controller' => 'question',
        'action' => 'search'
    ]
);

$router->handle($_SERVER['REQUEST_URI']);
