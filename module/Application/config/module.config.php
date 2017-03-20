<?php

return array(
    'router' => include 'router.php',
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'=> 'Zend\Db\Adapter\AdapterServiceFactory',
            'menu\default' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'menu\leftpanel' => 'Application\Navigation\Service\LeftpanelFactory',
            'menu\main' => function($sm){
                $config = $sm->get('config');
                $navigation = $config['navigation']['main'];
                $factory = new \Zend\Navigation\Service\ConstructedNavigationFactory($navigation);
                return $factory->createService($sm);
            },
            'Acl\Service' => function($sm) {
                $config = $sm->get('config');
                $service = new Application\Service\Acl();
                $service->setSm($sm)
                    ->setRoles($config['acl']['roles'])
                    ->setRules($config['acl']['rules'])
                    ->initResources($config['acl']['resources']);
                return $service;
            },
            'Zend\Session\SessionManager' => function ($sm) {
                $config = $sm->get('config');
                if (isset($config['session'])) {
                    $session = $config['session'];
                    $sessionConfig = null;
                    if (isset($session['config'])) {
                        $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                        $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                        $sessionConfig = new $class();
                        $sessionConfig->setOptions($options);
                    }
                    $sessionStorage = null;
                    if (isset($session['storage'])) {
                        $class = $session['storage'];
                        $sessionStorage = new $class();
                    }
                    $sessionSaveHandler = null;
                    if (isset($session['save_handler'])) {
                        $sessionSaveHandler = new Zend\Session\SaveHandler\Cache($sm->get($session['save_handler']));
                    }
                    $sessionManager = new Zend\Session\SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                    if (isset($session['validators'])) {
                        $chain = $sessionManager->getValidatorChain();
                        foreach ($session['validators'] as $validator) {
                            $validator = new $validator();
                            $chain->attach('session.validate', array($validator, 'isValid'));
                        }
                    }
                } else $sessionManager = new SessionManager();
                Zend\Session\Container::setDefaultManager($sessionManager);
                return $sessionManager;
            },
            'reCaptchaService' => function($sm) {
                $config = $sm->get('Config');
                return new Zend\Captcha\ReCaptcha($config['recaptcha']);
            },
            'Cache\Redis' => function($sm) {
                $config = $sm->get('Config');
                return Zend\Cache\StorageFactory::factory(array(
                    'adapter' => array(
                        'name' => '\Application\Cache\Storage\Adapter\Redis',
                        'options' => $config['redis']
                    ),
                    'plugins' => array(
                        'IgnoreUserAbort' => array(
                            'exitOnAbort' => true
                        )
                    )));
            },
            'Mailer' => function($sm) {
                $config = $sm->get('Config');
                $mailer = new Application\Service\Mailer($config['mailer']);
                $mailer->setRenderer($sm->get('ViewRenderer'));
                return $mailer;
            },
            'ErrorHandling' => function($sm) {
                return new Application\Service\ErrorHandling($sm->get('Zend\Log'));
            },
            'Zend\Log' => function ($sm) {
                $date = date('Y-m-d');
                $logger = new Zend\Log\Logger;
                $writer = new Zend\Log\Writer\Stream(__DIR__ . '/../../../data/logs/error_' . $date . '.txt');
                $writer->addFilter(new Zend\Log\Filter\Priority(Zend\Log\Logger::ERR));
                $logger->addWriter($writer);
                return $logger;
            },
            'Mail\Log' => function($sm) {
                $config = $sm->get('config');
                $mail = new Zend\Mail\Message();
                $mail->setFrom($config['mailer']['site_email'])
                    ->addTo(ERROR_MAILTO)
                    ->setSubject('Log report ' . date('Y-m-d') . ' from server ' . SERVER_ID);
                $logger = new Zend\Log\Logger;
                $logger->addWriter(new Zend\Log\Writer\Mail($mail, new Zend\Mail\Transport\Sendmail()));
                return $logger;
            },
            'Transaction' => function($sm) {
                return new Application\Transaction($sm);
            },
            'translator' => function($sm) {
                $config = $sm->get('Config');
                return Application\I18n\Translator\Translator::factory($config['translator']);
            },
            'Auth\Service' => function($sm) {
                $service = new Application\Service\Auth($sm->get('User\Table'));
                $service->setSm($sm);
                return $service;
            },
            'Cache\Memcache' => function($sm) {
                $config = $sm->get('Config');
                $cache = Zend\Cache\StorageFactory::factory(array(
                    'adapter' => 'Memcache',
                    'plugins' => array(
                        'exception_handler' => array('throw_exceptions' => false),
                        'serializer'
                    )));
                $cache->setOptions($config['memcache']);
                return $cache;
            },
            'Project\Table' => function($sm) {
                return new Application\Model\Project\Table($sm);
            },
            'Project\Task\Table' => function($sm) {
                return new Application\Model\Project\Task\Table($sm);
            },
            'Project\Member\Table' => function($sm) {
                return new Application\Model\Project\Member\Table($sm);
            },
            'Category\Table' => function($sm) {
                $table = new Application\Model\Category\Table($sm);
                return $table;
            },
            'User\Table' => function($sm) {
                return new Application\Model\User\Table($sm);
            },
            'User\Role\Table' => function($sm) {
                return new Application\Model\User\Role\Table($sm);
            },
            'User\Parent\Table' => function($sm) {
                return new Application\Model\User\Parents\Table($sm);
            },
            'User\CustomField\Table' => function($sm) {
                return new Application\Model\User\CustomField\Table($sm);
            },
            'Customer\Table' => function($sm) {
                return new Application\Model\Customer\Table($sm);
            },
            'Order\Table' => function($sm) {
                $table = new Application\Model\Order\Table($sm);
                return $table;
            },
            'Order\History\Table' => function($sm) {
                $table = new Application\Model\Order\History\Table($sm);
                return $table;
            },
            'Status\Table' => function($sm) {
                $table = new Application\Model\Status\Table($sm);
                return $table;
            },
            'Service\Table' => function($sm) {
                $table = new Application\Model\Service\Table($sm);
                return $table;
            },
        )
    ),
    'translator' => array(
        'locale' => 'en',
        'translation_file_patterns' => array(
            array(
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Auth' => 'Application\Controller\AuthController',
            'Application\Controller\Blog' => 'Application\Controller\BlogController',
            'Application\Controller\Admin\Index' => 'Application\Controller\Admin\IndexController',
            'Application\Controller\Admin\Report' => 'Application\Controller\Admin\ReportController',
            'Application\Controller\Admin\Rates' => 'Application\Controller\Admin\RatesController',
            'Application\Controller\Admin\Users' => 'Application\Controller\Admin\UsersController',
            'Application\Controller\Admin\Report' => 'Application\Controller\Admin\ReportController',
            'Application\Controller\Admin\Permissions'  =>  'Application\Controller\Admin\PermissionsController',
            'Application\Controller\Admin\Settings'  =>  'Application\Controller\Admin\SettingsController',
            'Application\Controller\Admin\Workflow'  =>  'Application\Controller\Admin\WorkflowController',
            
            'Application\Controller\Home\Index' => 'Application\Controller\Home\IndexController',
            'Application\Controller\Home\Account' => 'Application\Controller\Home\AccountController',
            'Application\Controller\Home\Payments\Index'  =>  'Application\Controller\Home\Payments\IndexController',
            'Application\Controller\Home\Payments\Services' => 'Application\Controller\Home\Payments\ServicesController',
            'Application\Controller\Home\Payments\Finances' => 'Application\Controller\Home\Payments\FinancesController',
            'Application\Controller\Home\Payments\Payrolls' => 'Application\Controller\Home\Payments\PayrollsController',
            'Application\Controller\Home\Payments\Invoicing' => 'Application\Controller\Home\Payments\InvoicingController',
            'Application\Controller\Home\Payments\Invoices' => 'Application\Controller\Home\Payments\InvoicesController',
            
            'Application\Controller\Home\Customer' => 'Application\Controller\Home\CustomerController',
            'Application\Controller\Home\Customers' => 'Application\Controller\Home\CustomersController',
            'Application\Controller\Home\Project\Index' => 'Application\Controller\Home\Project\indexController',
            'Application\Controller\Home\Project\News' => 'Application\Controller\Home\Project\NewsController',
            'Application\Controller\Home\Project\Tasks' => 'Application\Controller\Home\Project\TasksController',
            'Application\Controller\Home\Project\Activity' => 'Application\Controller\Home\Project\ActivityController',
            'Application\Controller\Home\Project\Roadmap' => 'Application\Controller\Home\Project\RoadmapController',
            'Application\Controller\Home\Project\Gantt' => 'Application\Controller\Home\Project\GanttController',
            'Application\Controller\Home\Project\Calendar'  =>  'Application\Controller\Home\Project\CalendarController',
            'Application\Controller\Home\Project\News' => 'Application\Controller\Home\Project\NewsController',
            'Application\Controller\Home\Project\Settings' => 'Application\Controller\Home\Project\SettingsController',
            'Application\Controller\Home\Project\Documents' => 'Application\Controller\Home\Project\DocumentsController',
            'Application\Controller\Home\Project\Forums' => 'Application\Controller\Home\Project\ForumsController',
            'Application\Controller\Home\Project' => 'Application\Controller\Home\ProjectController',
            'Application\Controller\Home\Projects' => 'Application\Controller\Home\ProjectsController',
            'Application\Controller\Home\Blog' => 'Application\Controller\Home\BlogController',
            'Application\Controller\Home\Orders' => 'Application\Controller\Home\OrdersController',
            'Application\Controller\Home\Reports' => 'Application\Controller\Home\ReportsController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../themes/{themeName}/layout/layout.phtml',
            'layout/auth' => __DIR__ . '/../themes/{themeName}/layout/auth.phtml',
            'layout/admin' => __DIR__ . '/../themes/{themeName}/layout/admin.phtml',
            'error/404' => __DIR__ . '/../themes/{themeName}/error/404.phtml',
            'error/index' => __DIR__ . '/../themes/{themeName}/error/index.phtml',
            'layout/print'  =>  __DIR__ . '/../themes/{themeName}/layout/print.phtml',
            'layout/print/report'  =>  __DIR__ . '/../themes/{themeName}/layout/print/report.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../themes/{themeName}',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'treeCheckbox'  =>  'Application\View\Helper\TreeCheckbox',
            'notification' => 'Application\View\Helper\Notification',
            'needBuildList' => 'Application\View\Helper\NeedBuildList',
            'needResourceList' => 'Application\View\Helper\NeedResourceList',
            'dateMessage' => 'Application\View\Helper\DateMessage',
            'dateNotification' => 'Application\View\Helper\DateNotification',
            'givesList' => 'Application\View\Helper\GivesList',
            'attrValueBlock' => 'Application\View\Helper\AttrValueBlock',
            'formElementUiSlider'   =>  'Application\View\Helper\FormElementUiSlider',
        ),
        'factories' => array(
            'themePath' =>  function($sm){
                return new Application\View\Helper\ThemePath($sm->getServiceLocator());
            },
            'cms' =>  function($sm){
                return new Application\View\Helper\Cms($sm->getServiceLocator());
            },
            'takeServer' => function($sm) {
                return new Application\View\Helper\TakeServer($sm->getServiceLocator()->get('TakeServer\Service'));
            },
            'baseDynamicPath' => function($sm) {
                return new Application\View\Helper\BaseDynamicPath($sm);
            },
            'baseStaticPath' => function($sm) {
                $config = $sm->getServiceLocator()->get('config');
                return new Application\View\Helper\BaseStaticPath($config['static_host']);
            },
            'sm' => function($sm) {
                return new Application\View\Helper\Sm($sm->getServiceLocator());
            },
            'auth' => function($sm) {
                $authService = $sm->getServiceLocator()->get('Auth\Service');
                return new Application\View\Helper\Auth($authService);
            },
            'acl' => function($sm) {
                return new Application\View\Helper\Acl($sm->getServiceLocator());
            },
            'projects' => function($sm) {
                return new Application\View\Helper\Projects($sm->getServiceLocator());
            },
            'flashMessages' => function($pm) {
                $flashmessenger = $pm->getServiceLocator()
                    ->get('ControllerPluginManager')
                    ->get('flashmessenger');
                return new Application\View\Helper\FlashMessages($flashmessenger);
            },
            'navigation' => function($pm) {
                $role = 'guest';
                $authService = $pm->getServiceLocator()->get('Auth\Service');
                if ($authService->getUserRow())
                    $role = $authService->getUserRow()->role;
                $navigation = $pm->get('Zend\View\Helper\Navigation');
                $navigation->setAcl($pm->getServiceLocator()->get('Acl\Service'))
                    ->setRole($role);
                return $navigation;
            }
        )
    ),
    'navigation' => include 'navigation.php',
    'acl' => include 'acl.php'
);
        