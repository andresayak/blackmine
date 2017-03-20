<?php

namespace Application\Model\Project\Task;

use Application\Model\Row as Prototype;
use Application\Model\Project\Row AS ProjectRow;
use Application\Model\User\Row AS UserRow;

class Row extends Prototype
{
    protected $_assigneeRow, $_authRow, $_projectRow, $_typeRow, $_statusRow, $_priorityRow;
    protected $_categoryRow;
    
    public function getTypeRow()
    {
        if($this->_typeRow === null){
            $this->_typeRow = $this->_getCustomFieldById($this->type_id);
        }
        return $this->_typeRow;
    }
    
    public function getStatusRow()
    {
        if($this->_statusRow === null){
            $this->_statusRow = $this->_getCustomFieldById($this->status_id);
        }
        return $this->_statusRow;
    }
    
    public function getPriorityRow()
    {
        if($this->_priorityRow === null){
            $this->_priorityRow = $this->_getCustomFieldById($this->priority_id);
        }
        return $this->_priorityRow;
    }
    
    public function getCategoryRow()
    {
        if($this->_categoryRow === null){
            $this->_categoryRow = $this->getSm()->get('User\CustomField\Table')->fetchBy('id', $this->category_id);
        }
        return $this->_categoryRow;
    }
    
    public function getAssigneeRow()
    {
        if($this->_assigneeRow === null){
            $this->_assigneeRow = $this->getSm()->get('User\Table')->fetchBy('id', $this->assignee_id);
        }
        return $this->_assigneeRow;
    }
    
    public function getAuthRow()
    {
        if($this->_authRow === null){
            $this->_authRow = $this->getSm()->get('User\Table')->fetchBy('id', $this->auth_id);
        }
        return $this->_authRow;
    }
    
    public function setAuthRow(UserRow $row)
    {
        $this->_authRow = $row;
        return $this;
    }
    
    public function getProjectRow()
    {
        if($this->_projectRow === null){
            $this->_projectRow = $this->getSm()->get('Project\Table')->fetchBy('id', $this->project_id);
        }
        return $this->_projectRow;
    }
    
    public function setProjectRow(ProjectRow $row)
    {
        $this->_projectRow = $row;
        return $this;
    }
    
    public function _getCustomFieldById($id)
    {
        return $this->getProjectRow()->getAuthRow()
            ->getCustomFieldRowset()->getBy('id', $id);
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