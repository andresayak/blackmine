<?php

namespace Application\Model\Status;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'statuses';
    
    protected $_cols = array(
        'id', 'name', 'is_default', 'is_close'
    );
    protected $_defaults = array(
    );
}