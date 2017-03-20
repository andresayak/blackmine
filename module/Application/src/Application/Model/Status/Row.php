<?php

namespace Application\Model\Status;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new Application\InputFilter\InputFilter;

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
            ));
            
            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}