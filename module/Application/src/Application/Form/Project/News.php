<?php

namespace Application\Form\Project;

use Application\Form\Form;

class News extends Form
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
