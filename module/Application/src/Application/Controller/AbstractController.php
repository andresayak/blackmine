<?php

namespace Application\Controller;

use Zend\Mvc\MvcEvent,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response;
use Zend\InputFilter\InputFilter;
use Application\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;

class AbstractController extends AbstractActionController
{
    public function isJson()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
            return true;
        }
        
        $headers = $request->getHeaders();
        if (!$headers->has('Accept')) {
            return false;
        }

        $accept = $headers->get('Accept');
        $match  = $accept->match('application/json');
        if (!$match || $match->getTypeString() == '*/*') {
            return false;
        }
        return true;
    }
    
    public function out($data, $paginator = null, $outMethod = null, $json_options = null)
    {
        $outMethod = ($outMethod === null)?'toArrayForApi':$outMethod;
        if($paginator){
            $data['list'] = $paginator->getCurrentItems()->{$outMethod}();
            $data['items_count'] = $paginator->getTotalItemCount();
            $data['page_count'] = count($paginator);
        }
        $data = $this->array_map_recursive($data);
        if($profiler = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')->getProfiler()){
            $time = 0;
            foreach($profiler->getProfiles() AS $row){
                $time+=$row['elapse'];
            }
            $data['profiler'] = array(
                'count' => count($profiler->getProfiles()), 
                'time' => $time, 
                'php-time'=>(microtime(true) - REQUEST_MICROTIME)
            );
            if($this->params()->fromQuery('profiler', false)){
                $data['profiler']['query'] = $profiler->getProfiles();
            }
        }
        $authService = $this->getServiceLocator()->get('Auth\Service');
        $config = $this->getServiceLocator()->get('config');
        $data['technicalWork'] = $config['workStatus'];
        $data['time_now'] = time();
        $data['time_request'] = (isset($_SERVER['REQUEST_TIME_FLOAT']))?$_SERVER['REQUEST_TIME_FLOAT']:false;
        $data['api_version']=API_VER;
        if(defined('SERVER_ID') and SERVER_ID){
            $data['server_id'] = SERVER_ID;
        }
        if($this->params()->fromQuery('print', false)){
            echo '<pre>';
            print_r($data);
            echo '</pre>';exit;
        }
        
        $this->getResponse()
            ->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
        $modal = new JsonModel($data);
        $modal->setJsonOptions($json_options);
        return $modal;
    }
    
    protected function array_map_recursive($arr) 
    {
        $rarr = array();
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $rarr[$k] = $this->array_map_recursive($v);
            } else {
                if ($v === null) {
                    unset($rarr[$k]);
                }
                else
                    $rarr[$k] = $v;
            }
        }
        return $rarr;
    }
    
    public function outError($errorMsg)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        return $this->out(array(
            'error'     =>  $errorMsg,
            'status'    =>  false
        ));
    }
    
    public function outOk(array $options = array(), $paginator = null, $outMethod = null)
    {
        $data = array('status'  =>  true);
        $data = $data+$options;
        return $this->out($data, $paginator, $outMethod);
    }
    
    protected function _redirect($action = 'index', $query = array(), $route = null)
    {
        return $this->redirect()->toRoute($route, array(
            'controller'=>  $this->params('__CONTROLLER__'),
            'action'    =>  $action
        ), array(
            'query' =>  $query
        ));
    }
    
    protected function getRow($tableName, $key = false)
    {
        $table = $this->getTable($tableName);
        if(!$key){
            $key = $table->getKey();
        }
        if(!$value = $this->params()->fromQuery($key)
             or !$row = $table->fetchByPK($value)
        )
            return false;
        return $row;
    }
    
    protected function getTable($name)
    {
        return $this->getServiceLocator()->get($name.'\Table');
    }
    
    protected function getForm($name)
    {
        return $this->getServiceLocator()->get($name.'\Form');
    }
    
    public function getAuthUser()
    {
        return $this->getServiceLocator()->get('Auth\Service')->getUserRow();
    }
    
    public function addMessage($value, $type = 'info')
    {
        if($value instanceof InputFilter){
            foreach($value->getMessages() AS $input=>$messages){
                foreach($messages AS $message)
                    $this->addMessage($input.': '.$message, $type);
            }
            return $this;
        }
        $namespace = $this->flashMessenger()->getNamespace();
        $this->flashMessenger()->setNamespace($type)
            ->addMessage($value)
            ->setNamespace($namespace);
    }
}