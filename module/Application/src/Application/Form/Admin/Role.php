<?php

namespace Application\Form\Admin;

use Application\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class Role extends Form
{
    protected $_sm;
    
    public function __construct(ServiceLocatorInterface $sm) 
    {
        $this->_sm = $sm;
        parent::__construct();
    }
    
    public function init()
    {
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'name',
            'type'  => 'text',
        ));
        $permission = $this->getPermissions();
        foreach($permission AS $items){
            foreach($items['childs'] AS $item){
                $this->add(array(
                    'name'  => $item['resource'],
                    'type'  => 'checkbox',
                    'options' => array(
                        'checked_value' => 'allow',
                        'unchecked_value' => 'deny',
                    )
                ));
            }
        }
    }
    
    public function getPermissions()
    {
        $config = $this->_sm->get('config');
        return $config['acl']['permissionLabels'];
    }
    
    protected function extract()
    {
        if (null !== $this->baseFieldset) {
            $name = $this->baseFieldset->getName();
            $values[$name] = $this->baseFieldset->extract();
        } else {
            $values = parent::extract();
        }
        if($permissionValues = $this->object->getPermissions()){
            $values = array_merge ($permissionValues, $values);
        }
        return $values;
    }
}
