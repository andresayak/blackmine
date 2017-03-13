<?php

namespace Application\Form\Project;

use Application\Form\Form;

class Task extends Form
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
        
        $this->add(array(
            'name' => 'type_id',
            'type'  => 'select',
        ));
        
        $this->add(array(
            'name' => 'category_id',
            'type'  => 'select',
        ));
        $this->add(array(
            'name'  =>  'priority_id',
            'type'  =>  'select'
        ));
    }
}
