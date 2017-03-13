<?php

namespace Application\Model\User;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'users';
    
    protected $_cols = array(
        'id', 'email',  'password', 'role', 'ban_status', 'name', 'commission', 'phone', 'skype'
    );
    protected $_defaults = array(
        'ban_status'    =>  'off',
        'commission'    =>  20
    );
}