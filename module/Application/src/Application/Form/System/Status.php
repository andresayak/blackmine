<?php

namespace Application\Form\System;

use Application\Form\Form;

class Status extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'order_id',
            'type'  => 'text',
        ));
        $this->add(array(
            'name'  => 'phone',
            'type'  => 'text',
        ));

        $inputFilter = new \Zend\InputFilter\InputFilter;
        $inputFilter->add(array(
            'name' => 'order_id',
            'required' => false,
            'filters'   =>  array(
                array(
                    'name'  =>  'StringTrim'
                )
            )
        ));
        $inputFilter->add(array(
            'name' => 'phone',
            'required' => false,
            'filters'   =>  array(
                array(
                    'name'  =>  'StringTrim'
                )
            )
        ));
        $this->setInputFilter($inputFilter);
    }
}