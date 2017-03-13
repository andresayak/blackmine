<?php
return array(
    'view_manager' => array(
        'defaultThemeName' =>  'default',
        'useThemeName'  =>  '{themeName}',
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'base_path' => '{domain}',
    ),
    'langs' =>  array(
        'availables' => array(
            'en'
        ),
        'default'   =>  'en'
    ),
    'mailer'   =>  array(
        'enable_background' => false,
        'status'    =>  true,
        'site_email'  =>  '{feedback.email}',
        'site_name' =>  '{feedback.name}',
        'feedback_emails'   =>  array(
        ),
        'errors'    =>  array(
        ),
         'smtp' => array(
            'name' => 'blackminemail',
            'host' => '{smtp.host}',
            'connection_class' => 'login',
            'port' => 25,
            'connection_config' => array(
                'username' => '{smtp.login}',
                'password' => '{smtp.password}',
                'ssl' => 'tls',
            )
        )
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
        'save_handler'  =>  'Cache\Memcache'
    ),
    'constants' =>  array(
    ),
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname={db.name};host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND           =>  'SET NAMES \'UTF8\', sql_mode = \'\'',
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY     =>  true
        ),
        'username' => '{db.username}',
        'password' => '{db.password}',
    ),
    'phpSettings'=>array(
        'memory_limit'                  =>  '256M',
        'display_startup_errors'        =>  0,
        'display_errors'                =>  0,
        'date.timezone'                 =>  'UTC',
    ),
    'log'   =>  './data/logs/',
    'memcache' =>  array(
        'namespace' =>  '{memcache.prefix}',
        'servers' => array('host' => '127.0.0.1')
    ),
    'recaptcha' => array(
        'name' => 'recaptcha',
        'enable'    =>  false,
        'private_key' => '{recaptcha.private_key}',
        'public_key' => '{recaptcha.public_key}',
    ),
    'leftmenu' => array(
        array(
            'routeRegex' => '/^payments(_|$)/',
            'pageIndex' => 'payments',
            'menuName' => 'main'
        ),
        array(
            'routeRegex' => '/^project-view/',
            'agruments' => 'project_id',
            'pageIndex' => 'project',
            'menuName' => 'main'
        ),
        array(
            'routeRegex' => '/^admin-?/',
            'pageIndex' => 'admin',
            'menuName' => 'main'
        )
    )
);
