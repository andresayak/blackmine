<?php

namespace Application\Model\User;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'users';
    
    protected $_defaults = array(
        'role'  =>  'member',
        'ban_status'    =>  'off',
        'commission'    =>  20
    );
}