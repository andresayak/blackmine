<?php

namespace Application\Model\User;

use Application\Model\Rowset as Prototype;

class Rowset extends Prototype
{
    public function toArrayForApi($recursive = true)
    {
        $data = array();
        foreach($this->getItems() AS $row){
            $data[] = $row->toArrayForApiBase();
        }
        return $data;
    }
}