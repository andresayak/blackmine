<?php

namespace Application\Model\Category;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new Application\InputFilter\InputFilter;

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}