<?php

namespace Application\Form\InputFilter\User;

use Application\InputFilter\InputFilter;

class Login extends InputFilter
{
    protected $_user_row;
    public function __construct($sm)
    {
        $this->setSm($sm);
        
        $table = $this->getSm()->get('User\Table');
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'break_chain_on_failure'=>true
                ),
                new \Zend\Validator\Db\RecordExists(
                    array(
                        'adapter'   =>  $table->getTableGateway()->getAdapter(),
                        'table'     =>  $table->getName(),
                        'field'     =>  'email',
                    )
                ),
                array(
                    'name'  =>  'Application\Validator\Callback',
                    'options'   =>  array(
                        'callback'  =>  array($this, 'checkUser'),
                        'messages'  =>  array('callbackValue'=>'User invalid')
                    ),
                    'break_chain_on_failure'=>true
                ),
            )
        ));
        
        $this->get('email')->setBreakOnFailure(true);
        
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                    'break_chain_on_failure'=>true
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 6
                    ),
                    'break_chain_on_failure'=>true
                ),
                array(
                    'name'  =>  'Application\Validator\PasswordHash',
                    'break_chain_on_failure'=>true
                ),
            ),
        ));
    }
    
    
    public function checkUser($email)
    {
        $this->_user_row = $this->_sm->get('User\Table')->fetchBy('email', $email);
        return (bool) $this->_user_row;
    }
    
    public function getUserRow()
    {
        return $this->_user_row;
    }
}