<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class Projects extends AbstractHelper
{
    protected $_sm;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        return $this;
    }
    
    public function __invoke()
    {
        return $this;
    }
    
    public function getItems()
    {
        return $this->_sm->get('Project\Table')->getRowset()->getItems();
    }
}