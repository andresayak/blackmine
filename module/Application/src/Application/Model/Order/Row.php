<?php

namespace Application\Model\Order;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    protected $_user_row, $_category_row, $_acceptor_row, $_history_rowset, $_status_row;
    
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new Application\InputFilter\InputFilter;
            
            $inputFilter->add(array(
                'name' => 'customer_id',
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
            
            $inputFilter->add(array(
                'name' => 'customer_name',
                'required' => true,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'customer_phone',
                'required' => true,
                'validators' => array(
                    array(
                        'name'  =>  'StringLength',
                        'options'    =>  array(
                            'min'   =>  9
                        )
                    ),
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'customer_email',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'customer_skype',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'price_work',
                'required' => false,
            ));
            
            $inputFilter->add(array(
                'name' => 'price_detail',
                'required' => false,
            ));
            
            $inputFilter->add(array(
                'name' => 'category_id',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'product_title',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'product_serialnumber',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'product_view',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'problem',
                'required' => false,
                'filters'   =>  array(
                    array(
                        'name'  =>  'Null'
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'product_options',
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
    
    public function getAcceptorRow()
    {
        if($this->_acceptor_row === null){
            $this->_acceptor_row = $this->getSm()->get('User\Table')->fetchBy('id', $this->acceptor_id);
        }
        return $this->_acceptor_row;
    }
    
    public function getCategoryRow()
    {
        if($this->_category_row === null){
            $this->_category_row = $this->getSm()->get('Category\Table')->fetchBy('id', $this->category_id);
        }
        return $this->_category_row;
    }
    
    public function getHistoryRowset()
    {
        if($this->_history_rowset === null){
            $this->_history_rowset = $this->getSm()->get('Order\History\Table')->fetchAllBy('order_id', $this->id);
        }
        return $this->_history_rowset;
    }
    
    public function getStatusRow()
    {
        if($this->_status_row === null){
            $this->_status_row = $this->getSm()->get('Status\Table')->fetchBy('id', $this->status_id);
        }
        return $this->_status_row;
    }
    
    public function getUserPrice()
    {
        return $this->price_work * $this->user_commission/100;
    }
}