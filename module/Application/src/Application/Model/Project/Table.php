<?php

namespace Application\Model\Project;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'projects';
    
    protected $_cols = array(
        'id', 'title', 'description', 'auth_id', 'time_add'
    );
    protected $_defaults = array(
    );
    
    public function getWhereByUser($user_id, $userProject)
    {
        $where = new \Zend\Db\Sql\Where();
        $items = array();
        foreach($userProject AS $userProjectRow){
            $items[] = $userProjectRow->project_id;
        }
        if(!count($items)){
            return array(
                'auth_id'   =>  $user_id
            );
        }else{
            $where->addPredicates(
                array(
                    new \Zend\Db\Sql\Predicate\In('id', $items),
                    'auth_id'   =>  $user_id
                ), 'OR'
            );
        }
        return $where;
    }
}