<?php

namespace Application\Model\Order\History;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'orders_history';
    
    protected $_cols = array(
        'id', 'user_id', 'order_id', 'auth_id', 'time_add', 'comment', 'status_id'
    );
    protected $_defaults = array(
    );
}