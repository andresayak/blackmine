<?php

namespace Application\Form\System;

use Application\Form\Form;

class GetCourse extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'phone',
            'type'  => 'text',
        ));
        $inputFilter = new \Zend\InputFilter\InputFilter;
        $inputFilter->add(array(
            'name' => 'phone',
            'required' => true,
        ));
        $this->setInputFilter($inputFilter);
    }
}