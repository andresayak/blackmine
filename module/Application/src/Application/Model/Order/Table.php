<?php

namespace Application\Model\Order;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'orders';
    
    protected $_cols = array(
        'id', 'user_id', 'customer_id', 'customer_name', 'customer_phone', 'status_id',
        'customer_email', 'customer_skype', 'status', 'time_add', 'time_update',
        'status', 'time_add', 'time_update', 'time_end', 'price_work', 'price_detail', 'price',
        'product_title', 'product_options', 'work_result', 'user_commission',
        'category_id', 'product_serialnumber', 'product_view', 'acceptor_id', 'problem'
    );
    protected $_defaults = array(
        'status'    =>  'new'
    );
}