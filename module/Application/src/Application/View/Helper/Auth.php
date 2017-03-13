<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class Auth extends AbstractHelper
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
    
    public function __invoke()
    {
        return $this;
    }
    
    public function is()
    {
        return ($this->getService()->getUserRow());
    }
    
    public function getUserRow()
    {
        return $this->getService()->getUserRow();
    }
}