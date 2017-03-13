<?php

namespace Application\Model\Project;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new \Application\InputFilter\InputFilter;

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'description',
                'required' => false,
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}