<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Http\Request as HttpRequest;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        $phpSettings = $config['phpSettings'];
        if($phpSettings) {
            foreach($phpSettings as $key => $value) {
                ini_set($key, $value);
            }
        }
        if(isset($config['constants'])){
            foreach ($config['constants'] as $key => $value) {
                if (!defined($key)) {
                    define($key, $value);
                }
            }
        }
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
        $eventManager->attach('dispatch', function($event){
            if($event->getRouteMatch() instanceof \Zend\Mvc\Router\Http\RouteMatch){
                $sm = $event->getApplication()->getServiceManager();
                $config = $sm->get('config');
                if(isset($config['ip_bans']) and in_array(IP_ADDRESS, $config['ip_bans'])){
                    header("HTTP/1.0 403 Forbidden");
                    exit;
                }
                $aclService = $sm->get('Acl\Service');
                $role = 'guest';
                $authService = $sm->get('Auth\Service');
                if($authService->getUserRow()){
                    $role = $authService->getUserRow()->role;
                }
                if (!$aclService->isAllowedByRoute($role, $event->getRouteMatch())) {
                    $response = $event->getResponse();
                    if($this->isJson($event->getRequest())){
                        $event->setViewModel(new JsonModel(array(array('error'=>'Access denied'))));
                        $response->setStatusCode(401);
                    }else{
                        $flash = $sm->get('ControllerPluginManager')->get('flashMessenger');
                        $flash->addErrorMessage('Access denied');
                        exit;
                        $response->setStatusCode(302);
                        $router = $event->getRouter();
                        $url    = $router->assemble(array(), array('name' => 'default'));
                        $response->getHeaders()->addHeaderLine('Location', $url);
                    }
                    $event->stopPropagation();  
                }
            }
        }, 100);
        $eventManager->attach('dispatch.error', function($event){
            $exception = $event->getResult()->exception;
            if ($exception) {
                $sm = $event->getApplication()->getServiceManager();
                $service = $sm->get('ErrorHandling');
                $service->logException($exception, $event->getApplication()->getServiceManager());
            }
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('layout/error');
        });
        //$eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRenderError'));
    }
    
    public function isJson($request){
        if ($request->isXmlHttpRequest()) {
            return true;
        }

        $headers = $request->getHeaders();
        if (!$headers->has('Accept')) {
            return false;
        }

        $accept = $headers->get('Accept');
        $match = $accept->match('application/json');
        if (!$match || $match->getTypeString() == '*/*') {
            return false;
        }
        return true;
    }
    
    public function onRenderError($e)
    {
        // must be an error
        if (!$e->isError()) {
            return;
        }

        // Check the accept headers for application/json
        $request = $e->getRequest();
        if (!$request instanceof HttpRequest) {
            return;
        }

        $headers = $request->getHeaders();
        if (!$headers->has('Accept')) {
            return;
        }

        $accept = $headers->get('Accept');
        $match  = $accept->match('application/json');
        if (!$match || $match->getTypeString() == '*/*') {
            // not application/json
            return;
        }
        // make debugging easier if we're using xdebug!
        ini_set('html_errors', 0); 

        // if we have a JsonModel in the result, then do nothing
        $currentModel = $e->getResult();
        if ($currentModel instanceof JsonModel) {
            return;
        }

        // create a new JsonModel - use application/api-problem+json fields.
        $response = $e->getResponse();
        $model = new JsonModel(array(
            "httpStatus" => $response->getStatusCode(),
            "title" => $response->getReasonPhrase(),
        ));

        // Find out what the error is
        $exception  = $currentModel->getVariable('exception');

        if ($currentModel instanceof ModelInterface && $currentModel->reason) {
            switch ($currentModel->reason) {
                case 'error-controller-cannot-dispatch':
                    $model->detail = 'The requested controller was unable to dispatch the request.';
                    break;
                case 'error-controller-not-found':
                    $model->detail = 'The requested controller could not be mapped to an existing controller class.';
                    break;
                case 'error-controller-invalid':
                    $model->detail = 'The requested controller was not dispatchable.';
                    break;
                case 'error-router-no-match':
                    $model->detail = 'The requested URL could not be matched by routing.';
                    break;
                default:
                    $model->detail = $currentModel->message;
                    break;
            }
        }

        if ($exception) {
            if ($exception->getCode()) {
                $e->getResponse()->setStatusCode($exception->getCode());
            }
            $model->detail = $exception->getMessage();

            // find the previous exceptions
            $messages = array();
            while ($exception = $exception->getPrevious()) {
                $messages[] = "* " . $exception->getMessage();
            };
            if (count($messages)) {
                $exceptionString = implode("n", $messages);
                $model->messages = $exceptionString;
            }
        }

        // set our new view model
        $model->setTerminal(true);
        $e->setResult($model);
        $e->setViewModel($model);
    }
    
    public function bootstrapSession($e)
    {
        if($_SERVER['PHP_SELF'] != '/usr/bin/phpunit'){
            $session = $e->getApplication()
                ->getServiceManager()
                ->get('Zend\Session\SessionManager');
            if(!$e->getRequest() instanceof \Zend\Console\Request 
                and $token = $e->getRequest()->getQuery('access_token', false)
            ){
                $session->setId($token);
            }
            $session->start();

            $container = new Container('initialized');
            if (!isset($container->init)) {
                 $session->regenerateId(true);
                 $container->init = 1;
            }
        }
    }
    
    public function getConfig()
    {
        $config = include __DIR__ . '/config/module.config.php';
        $config['service_manager']['factories'] = array_merge($config['service_manager']['factories'],
            array(
            )
        );
        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
