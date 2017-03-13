<?php

namespace Application\Form\Payment;

use Application\Form\Form;

class Service extends Form
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
