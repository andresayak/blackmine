<?php

namespace Application\Model\Project;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    protected $_authRow, $_memberRowset;
    
    public function getAuthRow()
    {
        if($this->_authRow === null){
            $this->_authRow = $this->getSm()->get('User\Table')
                ->fetchBy('id', $this->auth_id);
        }
        return $this->_authRow;
    }
    
    public function getMemberRowset()
    {
        if($this->_memberRowset === null){
            $this->_memberRowset = $this->getSm()->get('Project\Member\Table')
                ->fetchAllBy('project_id', $this->id);
        }
        return $this->_memberRowset;
    }
    
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