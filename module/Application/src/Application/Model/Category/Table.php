<?php

namespace Application\Model\Category;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'category';
    
    protected $_cols = array(
        'id', 'title'
    );
    protected $_defaults = array(
    );
}