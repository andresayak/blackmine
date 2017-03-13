<?php

namespace Application\Model\Project\Member;

use Application\Model\Table as Prototype;

class Table extends Prototype
{
    protected $_name = 'project_members';
    
    protected $_cols = array(
        'id', 'role_id', 'project_id', 'user_id'
    );
    protected $_defaults = array(
    );
}