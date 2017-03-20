<?php

namespace Application\Service;

use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\ServiceManager\ServiceManager;
use Zend\Permissions\Acl\Acl as BaseAcl,
    Zend\Mvc\MvcEvent;

class Acl extends BaseAcl
{
    protected $_roles;
    protected $_rules;

    public function getSm() 
    {
        return $this->_sm;
    }
    
    public function setSm(ServiceManager $serviceManager) 
    {
        $this->_sm = $serviceManager;
        $vars = get_object_vars($this);
        foreach ($vars AS $value) {
            if ($value instanceof Rowset) {
                $value->setSm($serviceManager);
            }
        }
        return $this;
    }
    
    public function initResources($resources)
    {
        $resources = $this->recursiveResources($resources);
        foreach ($this->_roles as $role) {
            $this->addRole(new Role($role['code']), $role['parent']);
            foreach ($this->_rules as $rule) {
                if ($rule['role'] == $role['code']) {
                    if ($rule['resource'] == 'all') {
                        if ($rule['permission'] == 'allow') {
                            $this->allow($role['code']);
                        }
                    } else {
                        if(array_search($rule['resource'], $resources) === false){
                            throw new \Exception('resource not set('.$rule['resource'].')');
                        }
                        switch ($rule['permission']) {
                            case 'allow':
                                $this->allow($role['code'], $rule['resource']);
                                break;
                            case 'deny':
                            default:
                                $this->deny($role['code'], $rule['resource']);
                                break;
                        }
                    }
                }
            }
        }
    }

    protected function recursiveResources($config, $parent = null)
    {
        $resources = array();
        foreach ($config as $resource) {
            $this->addResource(new Resource($resource['resource']), $parent);
            $resources[] = $resource['resource'];
            if(isset($resource['children']))
                $resources = array_merge($resources, $this->recursiveResources($resource['children'], $resource['resource']));
        }
        return $resources;
    }


    public function setRoles($roles)
    {
        $this->_roles = $roles;
        return $this;
    }

    public function setRules($rules)
    {
        $this->_rules = $rules;
        return $this;
    }
    
    public function isAllowedByRoute($role = null, $route)
    {
        $routeMatchName = $route->getMatchedRouteName();
        $controllerName = preg_replace(array('/\\\Controller\\\/', '/\\\/'), array('-', '/'), lcfirst($route->getParam('controller')));
        $resource = $part =  strtolower($controllerName).'-'.lcfirst($route->getParam('action'));
        while($part!=''){
            if($this->hasResource($part)){
                if(parent::isAllowed($role, $part)){
                    return true;
                }
                echo $role.' = '.$resource."\n";exit;
                return false;
            }
            $part = implode('-',explode('-', $part, -1));
        }
        if(($this->hasResource($resource) and parent::isAllowed($role, $resource))){
            return true;
        }else{
            echo $role.' = '.$resource."\n";exit;
            return false;
        }
    }
}