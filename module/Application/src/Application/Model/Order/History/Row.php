<?php

namespace Application\Model\Order\History;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    protected $_user_row, $_auth_row, $_status_row;
    
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new Application\InputFilter\InputFilter;
            
            
            $inputFilter->add(array(
                'name' => 'comment',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'status_id',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'user_id',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
    
    public function getUserRow()
    {
        if($this->_user_row === null){
            $this->_user_row = $this->getSm()->get('User\Table')->fetchBy('id', $this->user_id);
        }
        return $this->_user_row;
    }
    
    public function getAuthRow()
    {
        if($this->_auth_row === null){
            $this->_auth_row = $this->getSm()->get('User\Table')->fetchBy('id', $this->auth_id);
        }
        return $this->_auth_row;
    }
    
    public function getStatusRow()
    {
        if($this->_status_row === null){
            $this->_status_row = $this->getSm()->get('Status\Table')->fetchBy('id', $this->status_id);
        }
        return $this->_status_row;
    }
}