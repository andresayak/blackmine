<?php

namespace Application\Model\Project;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'projects';
    
    protected $_cols = array(
        'id', 'title', 'description', 'auth_id', 'time_add'
    );
    protected $_defaults = array(
    );
}