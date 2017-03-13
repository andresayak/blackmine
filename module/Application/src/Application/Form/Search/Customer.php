<?php

namespace Application\Form\Search;

use Application\Form\Form;
use Zend\Db\Sql\Predicate;

class Customer extends Form
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

        $inputFilter = new \Application\InputFilter\InputFilter;

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
            $whereLikes = array(
                new Predicate\Like('id', '%'.$query.'%'),
                new Predicate\Like('name', '%'.$query.'%'),
            );
            if($phoneQuery = $this->filterPhone($query)){
                $whereLikes[] = new Predicate\Like('phone', '%'.$phoneQuery.'%');
            }
            if(count($whereLikes)){
                $where->addPredicates(
                    $whereLikes, 'OR'
                );
            }
        }
        return $where;
    }
}
