<?php

namespace Application\Form\System;

use Application\Form\Form;

class Feedback extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'subject',
            'type'  => 'text',
        ));
        $this->add(array(
            'name'  => 'message',
            'type'  => 'textarea',
        ));
        
        $this->add(array(
            'name'  => 'email',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name'  => 'name',
            'type'  => 'text',
        ));
        $inputFilter = new \Zend\InputFilter\InputFilter;
        $inputFilter->add(array(
            'name' => 'subject',
            'required' => true,
        ));
        $inputFilter->add(array(
            'name' => 'message',
            'required' => true,
        ));
        $inputFilter->add(array(
            'name' => 'email',
            'required' => true,
        ));
        $inputFilter->add(array(
            'name' => 'name',
            'required' => true,
        ));
        $this->setInputFilter($inputFilter);
    }
}