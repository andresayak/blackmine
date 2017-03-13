<?php

namespace Application\Form;

use Application\Form\Form;

class Customer extends Form
{
    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'phone',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
        ));
        
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
