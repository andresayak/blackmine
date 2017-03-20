<?php

namespace Application\Model\Service;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    protected $_password;
    
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new Application\InputFilter\InputFilter;

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'description',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'price',
                'required' => false,
            ));
            
            $inputFilter->add(array(
                'name' => 'type',
                'required' => false,
                'validators' => array(
                )
            ));
            $inputFilter->add(array(
                'name' => 'parent_id',
                'required' => false,
            ));
            $inputFilter->add(array(
                'name' => 'user_id',
                'required' => false,
            ));
            
            $inputFilter->add(array(
                'name' => 'view_status',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'position',
                'required' => false,
            ));
            
            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}