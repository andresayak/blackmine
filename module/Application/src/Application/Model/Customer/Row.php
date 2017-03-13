<?php

namespace Application\Model\Customer;

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
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'  =>  'StringLength',
                        'options'    =>  array(
                            'max'   =>  256
                        )
                    ),
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'phone',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'  =>  'StringLength',
                        'options'    =>  array(
                            'min'   =>  9
                        )
                    ),
                    new \Zend\Validator\Db\NoRecordExists(
                        array(
                            'adapter'   =>  $this->getTable()->getTableGateway()->getAdapter(),
                            'table'     =>  $this->getTable()->getName(),
                            'field'     =>  'phone',
                            'exclude'   =>  (($this->id)?array(
                                'field' => 'id',
                                'value' => $this->id
                            ):null)
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'email',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'  =>  'StringLength',
                        'options'    =>  array(
                            'max'   =>  128
                        )
                    ),
                    new \Zend\Validator\Db\NoRecordExists(
                        array(
                            'adapter'   =>  $this->getTable()->getTableGateway()->getAdapter(),
                            'table'     =>  $this->getTable()->getName(),
                            'field'     =>  'email',
                            'exclude'   =>  (($this->id)?array(
                                'field' => 'id',
                                'value' => $this->id
                            ):null)
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'description',
                'required' => false,
                'validators' => array(
                    array(
                        'name'  =>  'StringLength',
                        'options'    =>  array(
                            'max'   =>  4000
                        )
                    ),
                )
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}