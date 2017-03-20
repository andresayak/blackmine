<?php

namespace Application\Form\Admin;

use Application\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class User extends Form
{
    protected $_sm;
    
    public function __construct(ServiceLocatorInterface $sm) 
    {
        $this->_sm = $sm;
        parent::__construct();
    }
    
    public function init()
    {
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'name',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'password',
            'type'  => 'password',
        ));
        
        $this->add(array(
            'name' => 'password_again',
            'type'  => 'password',
        ));
    }
}
