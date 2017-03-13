<?php

namespace Application\Form;

use Application\Form\Form;
use Application\Model\User\Row As UserRow;

class User extends Form
{
    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'text',
            )
        ));
        
        
        $this->add(array(
            'name' => 'role',
            'type'  => 'select',
            'attributes' => array(
                'type'  => 'select',
            ),
            'options'=>array(
                'value_options'  => UserRow::$roles
            )
        ));
        
        $this->add(array(
            'name' => 'ban_status',
            'type'  => 'checkbox',
            'options' => array(
                'checked_value' => 'on',
                'unchecked_value' => 'off',
            )
        ));
        
        $this->add(array(
            'name' => 'password',
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
            'name' => 'skype',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'commission',
            'type'  => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'name',
            'type'  => 'text',
        ));
        
        $this->add(array(
            'name' => 'password_change',
            'type'  => 'checkbox',
        ));
    }
}
