<?php

return array(
    'roles' => array(
        array(
            'code' => 'guest',
            'parent' => null,
            'priority' => 0
        ),
        array(
            'code' => 'member',
            'parent' => 'guest',
            'priority' => 10
        ),
        array(
            'code' => 'moderator',
            'parent' => 'member',
            'priority' => 20
        ),
        array(
            'code' => 'admin',
            'parent' => 'moderator',
            'priority' => 100
        ),
    ),
    'resources' => array(
        'default' => array(
            'resource' => 'application-default',
            'children' => array(
                array(
                    'resource' => 'application-index'
                ),
            ),
        ),
        'admin' =>  array(
            'resource'  =>  'application-admin',
            'children' => array(
                array(
                    'resource' => 'application-admin/index',
                ),
                array(
                    'resource'  =>  'application-admin/users'
                ),
                array(
                    'resource'  =>  'application-admin/permissions',
                    'children' => array(
                        array(
                            'resource'  =>  'application-admin/permissions-add'
                        ),
                    )
                ),
                array(
                    'resource'  =>  'application-admin/workflow'
                ),
                array(
                    'resource'  =>  'application-admin/rates'
                ),
                array(
                    'resource'  =>  'application-admin/settings'
                )
            )
        ),
        'home' => array(
            'resource' => 'application-home',
            'children' => array(
                array(
                    'resource'  =>  'application-home/index',
                ),
                array(
                    'resource'  =>  'application-home/account',
                ),
                array(
                    'resource'  =>  'application-home/payments',
                    'children' => array(
                        array(
                            'resource'  =>  'application-home/payments/services',
                            'children' => array(
                                array(
                                    'resource'  =>  'application-home/payments/services-add'
                                ),
                            )
                        ),
                        array(
                            'resource'  =>  'application-home/payments/index'
                        ),
                        array(
                            'resource'  =>  'application-home/payments/finances'
                        ),
                        array(
                            'resource'  =>  'application-home/payments/payrolls'
                        ),
                        array(
                            'resource'  =>  'application-home/payments/invoicing'
                        ),
                        array(
                            'resource'  =>  'application-home/payments/invoices'
                        ),
                    )
                ),
                
                array(
                    'resource'  =>  'application-home/projects',
                    'children' => array(
                        array(
                            'resource'  =>  'application-home/project-add',
                        ),
                        array(
                            'resource'  =>  'application-home/project-edit',
                        ),
                        array(
                            'resource'  =>  'application-home/project-remove',
                        ),
                        array(
                            'resource'  =>  'application-home/project-view'
                        )
                    )
                ),
                array(
                    'resource'  =>  'application-home/project/index',
                    'children' => array(
                        array(
                            'resource'  =>  'application-home/project/tasks'
                        ),
                        array(
                            'resource'  =>  'application-home/project/news'
                        ),
                        array(
                            'resource'  =>  'application-home/project/documents'
                        ),
                        array(
                            'resource'  =>  'application-home/project/roadmap'
                        ),
                        array(
                            'resource'  =>  'application-home/project/settings'
                        ),
                        array(
                            'resource'  =>  'application-home/project/gantt'
                        ),
                        array(
                            'resource'  =>  'application-home/project/calendar'
                        ),
                        array(
                            'resource'  =>  'application-home/project/activity'
                        ),
                        array(
                            'resource'  =>  'application-home/project/forums'
                        )
                    )
                ),
                array(
                    'resource'  =>  'application-admin/report'
                ),
                array(
                    'resource'  =>  'application-home/users',
                    'children' => array(
                        array(
                            'resource'  =>  'application-home/users-add',
                        ),
                        array(
                            'resource'  =>  'application-home/users-edit',
                        ),
                        array(
                            'resource'  =>  'application-home/users-remove',
                        )
                    )
                ),
                array(
                    'resource'  =>  'application-home/customers',
                    'children' => array(
                        array(
                            'resource'  =>  'application-home/customer-add',
                        ),
                        array(
                            'resource'  =>  'application-home/customer-edit',
                        ),
                        array(
                            'resource'  =>  'application-home/customer-remove',
                        )
                    )
                ),
                array(
                    'resource'  =>  'application-home/orders',
                    'children' => array(
                        array(
                            'resource'  =>  'application-home/orders-add',
                        ),
                        array(
                            'resource'  =>  'application-home/orders-edit',
                        ),
                        array(
                            'resource'  =>  'application-home/orders-remove',
                        ),
                        array(
                            'resource'  =>  'application-home/orders-print',
                        )
                    )
                ),
                array(
                    'resource'  =>  'application-home/reports',
                ),
            )
        ),
        'auth' => array(
            'resource' => 'application-auth',
            'children' => array(
                array(
                    'resource' => 'application-auth-index',
                ),
            )
        )
    ),
    'rules' => array(
        array(
            'role' => 'guest',
            'resource' => 'application-default',
            'permission' => 'allow'
        ),
        array(
            'role' => 'guest',
            'resource' => 'application-home',
            'permission' => 'deny'
        ),
        array(
            'role' => 'admin',
            'resource' => 'application-home',
            'permission' => 'allow'
        ),
        array(
            'role'  =>  'admin',
            'resource'  =>  'application-admin',
            'permission' => 'allow'
        ),
        array(
            'role' => 'moderator',
            'resource' => 'application-home',
            'permission' => 'allow'
        ),
        array(
            'role' => 'member',
            'resource' => 'application-home',
            'permission' => 'allow'
        ),
        
        array(
            'role' => 'guest',
            'resource'=>'application-auth',
            'permission' => 'allow'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/reports',
            'permission' => 'deny'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/customers',
            'permission' => 'deny'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/users',
            'permission' => 'deny'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/orders-remove',
            'permission' => 'deny'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/orders-edit',
            'permission' => 'deny'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/orders-add',
            'permission' => 'deny'
        ),
        array(
            'role' => 'member',
            'resource'  =>  'application-home/orders-print',
            'permission' => 'deny'
        ),
        array(
            'role' => 'moderator',
            'resource'  =>  'application-home/users-remove',
            'permission' => 'deny'
        ),
        array(
            'role' => 'moderator',
            'resource'  =>  'application-home/customer-remove',
            'permission' => 'deny'
        ),
        array(
            'role' => 'moderator',
            'resource'  =>  'application-home/orders-remove',
            'permission' => 'deny'
        ),
    )
);
