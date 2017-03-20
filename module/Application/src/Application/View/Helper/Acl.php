<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class Acl extends AbstractHelper
{
    protected $_service;
    public function __construct($service)
    {
        $this->_service = $service;
        return $this;
    }
    
    public function getService()
    {
        return $this->_service;
    }
    
    public function __invoke($routeMatchName)
    {
        $authService = $this->getService()->get('Auth\Service');
        $aclService = $this->getService()->get('Acl\Service');
        $role = 'guest';
        if($authService->getUserRow()){
            $role = $authService->getUserRow()->role;
        }
        return $aclService->isAllowed($role, $routeMatchName);
    }
}