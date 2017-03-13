<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class FlashMessages extends AbstractHelper
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
        $namespaces = array(
            'error', 'success',
            'info', 'warning'
        );
        $messages = array();

        foreach ($namespaces as $ns) {
            $this->getService()->setNamespace($ns);
            $messages[$ns] = $this->getService()->getCurrentMessages();
        }
        return $this->getView()->partial('application/flashmessages.phtml', array('messages'=>$messages));
    }
}