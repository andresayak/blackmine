<?php

namespace Application\Model\User\Parents;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'user_parents';
    
    protected $_defaults = array(
        'is_admin'  =>  'off'
    );
}