<?php

namespace Application\Form;

use Application\Form\Form;

class Order extends Form
{
    protected $_user_rowset, $_category_rowset, $_customer_rowset;
    
    public function init()
    {
        $this->setAttribute('method', 'post');
        
        $options = array(
            NULL=>  ' - - '
        );
        foreach($this->_customer_rowset->getItems() As $customerRow){
            $options[$customerRow->id] = $customerRow->name; 
        }
        
        $this->add(array(
            'name' => 'customer_id',
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
                $options[$userRow->id] = $userRow->name.' '.$userRow->commission.'%'; 
        }
        $this->add(array(
            'name' => 'user_id',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $options
            )
        ));
        $this->add(array(
            'name' => 'customer_phone',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'customer_email',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'customer_skype',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'customer_name',
            'type'  => 'text',
        ));
        
        
        $this->add(array(
            'name' => 'customer_phone',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'customer_skype',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'price_work',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'price_work',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'price_detail',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'work_result',
            'type'  => 'text',
        ));
        
        $options = array(
            NULL=>  ' - - '
        );
        foreach($this->_category_rowset->getItems() As $categoryRow){
            $options[$categoryRow->id] = $categoryRow->title; 
        }
        
        $this->add(array(
            'name' => 'category_id',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $options
            )
        ));
        
        $this->add(array(
            'name' => 'product_title',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'product_serialnumber',
            'type'  => 'textarea',
        ));
        
        $this->add(array(
            'name' => 'product_view',
            'type'  => 'textarea',
        ));
        
        $this->add(array(
            'name' => 'problem',
            'type'  => 'textarea',
        ));
        
        $this->add(array(
            'name' => 'product_options',
            'type'  => 'textarea',
        ));
        
    }
    
    public function setUserRowset($rowset)
    {
        $this->_user_rowset = $rowset;
        return ;
    }
    
    public function setCategoryRowset($rowset)
    {
        $this->_category_rowset = $rowset;
        return ;
    }
    
    public function setCustomerRowset($rowset)
    {
        $this->_customer_rowset = $rowset;
        return ;
    }
}
