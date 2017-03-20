<?php

return array(
    'routes' => array(
        'default' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/[:controller[/:action]]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'index',
                ),
            ),
        ),
        'login'   =>  array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/login',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'auth',
                    'action' => 'index',
                ),
            ),
        ),
        'pricing'   =>  array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/pricing',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'pricing',
                ),
            ),
        ),
        'service'   =>  array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/service',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'service',
                ),
            ),
        ),
        'faq'   =>  array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/faq',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'faq',
                ),
            ),
        ),
        'about' => array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/about',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'about',
                ),
            ),
        ),
        'contacts' => array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/contacts',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'contacts',
                ),
            ),
        ),
        'home' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home[/:action]',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'index',
                    'action' => 'index',
                ),
            ),
        ),
        'user' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/users[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'users',
                    'action' => 'index',
                ),
            ),
        ),
        'projects' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/projects[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'projects',
                    'action' => 'index',
                ),
            ),
        ),
        'account'   =>  array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/account[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'account',
                    'action' => 'index',
                ),
            ),
        ),
        'payments' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/payments[/:controller][/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home\Payments',
                    'controller' => 'index',
                    'action' => 'index',
                ),
            ),
        ),
        'customer' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/customer[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'customer',
                    'action' => 'index',
                ),
            ),
        ),
        'customers' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/customers[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'customers',
                    'action' => 'index',
                ),
            ),
        ),
        'reports' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/reports[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'reports',
                    'action' => 'index',
                ),
            ),
        ),
        'user-view' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/user[/:id]',
                'constraints' => array(
                    'id' => '[0-9]+',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'user',
                    'action' => 'view',
                ),
            ),
        ),
        'report-view' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/report[/:id]',
                'constraints' => array(
                    'id' => '[0-9]+',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'report',
                    'action' => 'view',
                ),
            ),
        ),
        
        'admin' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/admin[/:controller[/:action]]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Admin',
                    'controller' => 'index',
                    'action' => 'index',
                ),
            ),
        ),
        
        'project-view' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/project[/:project_id[/:controller[/:action]]]',
                'constraints' => array(
                    'project_id' => '[0-9]+',
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home\Project',
                    'controller' => 'index',
                    'action' => 'index',
                ),
            ),
        ),
        'order' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/home/orders[/:action]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller\Home',
                    'controller' => 'orders',
                    'action' => 'index',
                ),
            ),
        ),
        'logout' => array(
            'type' => 'Literal',
            'options' => array(
                'route' => '/logout',
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'logout',
                ),
            ),
        ),
    ),
);
