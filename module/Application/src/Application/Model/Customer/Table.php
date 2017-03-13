<?php

namespace Application\Model\Customer;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'customers';
    
    protected $_cols = array(
        'id', 'title', 'phone', 'email', 'description'
    );
    protected $_defaults = array(
    );
}