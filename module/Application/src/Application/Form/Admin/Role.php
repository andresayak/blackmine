<?php

namespace Application\Form\Admin;

use Application\Form\Form;

class Role extends Form
{
    public function init()
    {
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'description',
            'type'  => 'textarea',
        ));
    }
}
