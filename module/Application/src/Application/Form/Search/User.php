<?php

namespace Application\Form\Search;

use Application\Form\Form;
use Zend\Db\Sql\Predicate;
use Application\Model\User\Row As UserRow;

class User extends Form
{
    public function init()
    {
        $this->setAttribute('method', 'get');
        $this->add(array(
            'name' => 'query',
            'attributes' => array(
                'type'  => 'text',
            )
        ));

        
        $this->add(array(
            'name' => 'role',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => array_merge(array(
                    null    =>  ' - всі - ',
                ), UserRow::$roles)
            )
        ));
        
        $this->add(array(
            'name' => 'ban_status',
            'type'  => 'select',
            'options'=>array(
                'value_options'  => array(
                    null    =>  ' - всі - ',
                    'on'    =>  'Заблоковані',
                    'off'   =>  'Не блоковані',
                )
            )
        ));
        
        $inputFilter = new \Application\InputFilter\InputFilter;

        $inputFilter->add(array(
            'name' => 'ban_status',
            'required' => false,
        ));
        
        $inputFilter->add(array(
            'name' => 'role',
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
        if($query = $this->get('query')->getValue()){
            $where->addPredicates(
                array(
                    new Predicate\Like('id', '%'.$query.'%'),
                    new Predicate\Like('name', '%'.$query.'%'),
                    new Predicate\Like('email', '%'.$query.'%')
                ), 'OR'
            );
        }
        
        if($role = $this->get('role')->getValue()){
            $where->equalTo('role', $role);
        }
        if($role = $this->get('ban_status')->getValue()){
            $where->equalTo('ban_status', $role);
        }
        return $where;
    }
}
