<?php

namespace Application\Form\Project;

use Application\Form\Form;
use Application\Model\User\CustomField\Rowset AS CustomFieldRowset;
use Application\Model\Project\Member\Rowset AS MemberRowset;
use Zend\Form\FormInterface;

class Task extends Form
{
    protected $_customFieldRowset, $_assigneeRowset;
    
    public function init()
    {
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'description',
            'type'  => 'textarea',
        ));
        
        $fieldNames = array();
        $defaultFields = array();
        foreach($this->_customFieldRowset->getItems() As $itemRow){
            if(!isset($fieldNames[$itemRow->group])){
                $fieldNames[$itemRow->group] = array();
            }
            $fieldNames[$itemRow->group][$itemRow->id] = $itemRow->name;
            if($itemRow->is_default){
                $defaultFields[$itemRow->group.'_id'] = $itemRow->id;
            }
        }
        $fields = array(
            'type', 'priority', 'category', 'status'
        );
        foreach($fields AS $field){
            $optionValues = (isset($fieldNames[$field]))?$fieldNames[$field]:array();
            $this->add(array(
                'name' => $field.'_id',
                'type'  => 'select',
                'options'=>array(
                    'value_options'  => $optionValues
                )
            ));
        }
        
        $optionValues = array(
            NULL    =>  ' - -'
        );
        foreach($this->_assigneeRowset->getItems() As $itemRow){
            $optionValues[$itemRow->user_id] = $itemRow->getUserRow()->name;
        }
        $this->add(array(
            'name' => 'assignee_id',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $optionValues
            )
        ));
        
        $this->setData($defaultFields);
    }
    
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED) 
    {
        $this->setCustomFieldRowset($object->getProjectRow()->getAuthRow()->getCustomFieldRowset());
        $this->setAssigneeRowset($object->getProjectRow()->getMemberRowset());
        parent::bind($object, $flags);
    }
    
    public function setCustomFieldRowset(CustomFieldRowset $rowset)
    {
        $this->_customFieldRowset = $rowset;
        return $this;
    }
    
    public function setAssigneeRowset(MemberRowset $rowset)
    {
        $this->_assigneeRowset = $rowset;
        return $this;
    }
    
}
