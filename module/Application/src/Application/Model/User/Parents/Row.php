<?php

namespace Application\Model\User\Parents;

use Application\Model\Row as Prototype;

class Row extends Prototype
{
    protected $_userRow, $_parentRow;
    
    public function getUserRow()
    {
        if($this->_userRow === null){
            $this->_userRow = $this->getSm()->get('User\Table')->fetchBy('id', $this->user_id);
        }
        return $this->_userRow;
    }
    
    public function getParentRow()
    {
        if($this->_parentRow === null){
            $this->_parentRow = $this->getSm()->get('User\Table')->fetchBy('id', $this->parent_id);
        }
        return $this->_parentRow;
    }
}