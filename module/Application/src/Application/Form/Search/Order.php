<?php

namespace Application\Form\Search;

use Application\Form\Form;
use Zend\Db\Sql\Predicate;

class Order extends Form
{
    protected $_user_rowset, $_category_rowset, $_status_rowset;

    public function init()
    {
        $this->setAttribute('method', 'get');
        $this->add(array(
            'name' => 'query',
            'attributes' => array(
                'type'  => 'text',
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
        
        $options = array(
            NULL=>  ' - - '
        );
        /*
        foreach($this->_status_rowset->getItems() As $statusRow){
            $options[$statusRow->id] = $statusRow->name; 
        }*/

        $this->add(array(
            'name' => 'status',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $options
            )
        ));
        
        $options = array(
            NULL=>  ' - - '
        );
        /*
        foreach($this->_category_rowset->getItems() As $categoryRow){
            $options[$categoryRow->id] = $categoryRow->title; 
        }
        */
        $this->add(array(
            'name' => 'category_id',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => $options
            )
        ));
        
        $inputFilter = new \Application\InputFilter\InputFilter;

        $inputFilter->add(array(
            'name' => 'user_id',
            'required' => false,
        ));
        
        $inputFilter->add(array(
            'name' => 'status',
            'required' => false,
        ));
        
        $inputFilter->add(array(
            'name' => 'category_id',
            'required' => false,
        ));

        $inputFilter->add(array(
            'name' => 'query',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
        )));
        $this->setInputFilter($inputFilter);
    }
    
    public function getWhere()
    {
        $where = new \Zend\Db\Sql\Where();
        if($query = trim($this->get('query')->getValue())){
            $whereLikes = array(
                new Predicate\Like('id', '%'.$query.'%'),
                new Predicate\Like('customer_name', '%'.$query.'%'),
            );
            if($phoneQuery = $this->filterPhone($query)){
                $whereLikes[] = new Predicate\Like('customer_phone', '%'.$phoneQuery.'%');
            }
            if(count($whereLikes)){
                $where->addPredicates(
                    $whereLikes, 'OR'
                );
            }
        }
        if($category_id = $this->get('category_id')->getValue()){
            $where->equalTo('category_id', $category_id);
        }
        if($user_id = $this->get('user_id')->getValue()){
            $where->equalTo('user_id', $user_id);
        }
        if($status_id = $this->get('status')->getValue()){
            $where->equalTo('status_id', $status_id);
        }
        return $where;
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
    
    public function setStatusRowset($rowset)
    {
        $this->_status_rowset = $rowset;
        return ;
    }
    
}
