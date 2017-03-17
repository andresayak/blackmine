<?php

namespace Application\Model\User\CustomField;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new Application\InputFilter\InputFilter;
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
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
                'name' => 'name',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'role',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'password',
                'required' => false,
            ));
            
            $inputFilter->add(array(
                'name' => 'password_change',
                'required' => false,
                'validators' => array(
                )
            ));
            $inputFilter->add(array(
                'name' => 'ban_status',
                'required' => false,
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}