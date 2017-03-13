<?php

namespace Application\Model\Project\Member;

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
            
            $inputFilter->add(array(
                'name' => 'phone',
                'required' => true,
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
                'name' => 'skype',
                'required' => false,
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