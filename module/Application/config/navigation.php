<?php

return array(
    'default' => array(
        array(
            'label' => 'Home',
            'route' => 'default',
            'params' => array(
            )
        ),
        array(
            'label' => 'Pricing & Buy',
            'route' => 'pricing',
            'params' => array(
            )
        ),
        array(
            'label' => 'FAQ',
            'route' => 'faq',
            'params' => array(
            )
        ),
        array(
            'label' => 'Services',
            'route' => 'service',
            'params' => array(
            ),
            'pages' => array(
                array(
                    'label' => 'service 1',
                    'route' => 'service',
                    'params' => array(
                    )
                ),
                array(
                    'label' => 'service 2',
                    'route' => 'service',
                    'params' => array(
                    )
                ),
                array(
                    'label' => 'service 3',
                    'route' => 'service',
                    'params' => array(
                    )
                ),
            )
        ),
        array(
            'label' => 'Contacts',
            'route' => 'contacts',
            'params' => array(
            )
        ),
    ),
    'main' => array(
        'project'=>array(
            'visible'   =>  false,
            'label' => 'Projects',
            'route' => 'projects',
            'resource' => 'application-home/projects',
            'pages' => array(
                array(
                    'icon'  =>  'fa fa-tasks',
                    'label' => 'Tasks',
                    'route' => 'project-view',
                    'resource' => 'application-home/project/tasks',
                    'params' => array(
                        'controller' => 'tasks'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-newspaper-o',
                    'label' => 'News',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'news'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-history',
                    'label' => 'Activity',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'activity'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-calendar-check-o',
                    'label' => 'Gantt',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'gantt'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-calendar',
                    'label' => 'Calendar',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'calendar'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-file-text-o',
                    'label' => 'Documents',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'documents'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-comments-o',
                    'label' => 'Forums',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'forums'
                    ),
                ),
                array(
                    'icon'=>'fa fa-cogs',
                    'label' => 'Settings',
                    'route' => 'project-view',
                    'resource' => 'application-home/project-view',
                    'params' => array(
                        'controller' => 'settings'
                    )
                )
            )
        ),
        array(
            'label' => 'Customers',
            'route' => 'customers',
            'resource' => 'application-home/customers',
        ),
        'payments'=>array(
            'label' => 'Payments',
            'route' => 'payments',
            'resource' => 'application-home/payments',
            'params' => array(
            ),
            'pages' =>  array(
                array(
                    'icon'  =>  'fa fa-suitcase',
                    'label' => 'Services',
                    'route' => 'payments',
                    'resource' => 'application-home/payments/services',
                    'params' => array(
                        'controller'    =>  'services'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-balance-scale',
                    'label' => 'Personal finances',
                    'route' => 'payments',
                    'resource' => 'application-home/payments/finances',
                    'params' => array(
                        'controller'    =>  'finances'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-money',
                    'label' => 'Payrolls',
                    'route' => 'payments',
                    'resource' => 'application-home/payments/payrolls',
                    'params' => array(
                        'controller'    =>  'payrolls'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-file-pdf-o',
                    'label' => 'Invoices',
                    'route' => 'payments',
                    'resource' => 'application-home/payments/invoices',
                    'params' => array(
                        'controller'    =>  'invoices'
                    ),
                ),
            )
        ),
        'admin'=>array(
            'label' => 'Administration',
            'route' => 'admin',
            'resource' => 'application-admin',
            'params' => array(
            ),
            'pages' =>  array(
                array(
                    'icon'  =>  'fa fa-users',
                    'label' => 'Users',
                    'route' => 'admin',
                    'resource' => 'application-admin/users',
                    'params' => array(
                        'controller'    =>  'users'
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-lock',
                    'label' => 'Roles and permissions',
                    'route' => 'admin',
                    'resource' => 'application-admin/permissions',
                    'params' => array(
                        'controller' => 'permissions',
                    ),
                ),
                array(
                    'icon'  =>  'fa fa-map-signs',
                    'label' => 'Workflow',
                    'route' => 'admin',
                    'resource' => 'application-admin/workflow',
                    'params' => array(
                        'controller' => 'workflow',
                    ),
                    'pages' =>  array(
                        array(
                            'label' =>  'Status transitions',
                            'route' => 'admin',
                            'resource' => 'application-admin/workflow',
                            'params' => array(
                                'controller' => 'workflow',
                                'action'    =>  'index'
                            )
                        ),
                        array(
                            'label' => 'Trackers',
                            'route' => 'admin',
                            'resource' => 'application-admin/workflow',
                            'params' => array(
                                'controller' => 'workflow',
                                'action'    =>  'trackers'
                            )
                        ),
                        array(
                            'label' => 'Issue statuses',
                            'route' => 'admin',
                            'resource' => 'application-admin/workflow',
                            'params' => array(
                                'controller' => 'workflow',
                                'action'    =>  'statuses'
                            )
                        ),
                    )
                ),
                array(
                    'icon'  =>  'fa fa-cogs',
                    'label' => 'Settings',
                    'route' => 'admin',
                    'resource' => 'application-admin/settings',
                    'params' => array(
                        'controller' => 'settings',
                    ),
                    'pages' =>  array(
                        array(
                            'label' => 'General',
                            'route' => 'admin',
                            'resource' => 'application-admin/settings',
                            'params' => array(
                                'controller' => 'settings',
                                'action'    =>  'index'
                            )
                        ),
                        array(
                            'label' => 'Display',
                            'route' => 'admin',
                            'resource' => 'application-admin/settings',
                            'params' => array(
                                'controller' => 'settings',
                                'action'    =>  'display'
                            )
                        ),
                        array(
                            'label' => 'Authentication',
                            'route' => 'admin',
                            'resource' => 'application-admin/settings',
                            'params' => array(
                                'controller' => 'settings',
                                'action'    =>  'authentication'
                            )
                        ),
                        array(
                            'label' => 'Tracking',
                            'route' => 'admin',
                            'resource' => 'application-admin/settings',
                            'params' => array(
                                'controller' => 'settings',
                                'action'    =>  'tracking'
                            )
                        ),
                        array(
                            'label' => 'Email notifications',
                            'route' => 'admin',
                            'resource' => 'application-admin/settings',
                            'params' => array(
                                'controller' => 'settings',
                                'action'    =>  'email'
                            )
                        ),
                    )
                )
            )
        ),
        array(
            'label' => 'Reports',
            'route' => 'reports',
            'resource'    =>  'application-home/reports',
            'params' => array(
                
            ),
        ),
    ),
);

