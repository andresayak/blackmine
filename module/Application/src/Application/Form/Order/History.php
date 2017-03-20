<?php

namespace Application\Form\Order;

use Application\Form\Form;

class History extends Form
{
    protected $_user_rowset, $_status_rowset;
    
    public function init()
    {
        $this->setAttribute('method', 'post');
        
        $options = array(
            NULL=>  ' - - '
        );
        foreach($this->_status_rowset->getItems() As $statusRow){
            $options[$statusRow->id] = $statusRow->name; 
        }
        $this->add(array(
            'name' => 'status_id',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $options
            )
        ));
        
        $options = array(
            NULL=>  ' - - '
        );
        foreach($this->_user_rowset->getItems() As $userRow){
            if($userRow->role == 'member')
                $options[$userRow->id] = $userRow->name.' '.$userRow->commission.' %'; 
        }
        $this->add(array(
            'name' => 'user_id',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $options
            )
        ));
        
        
        $this->add(array(
            'name' => 'comment',
            'type'  => 'textarea',
        ));
    }
    
    public function setUserRowset($rowset)
    {
        $this->_user_rowset = $rowset;
        return ;
    }
    
    public function setStatusRowset($rowset)
    {
        $this->_status_rowset = $rowset;
        return ;
    }
    
}
