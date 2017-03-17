<?php

namespace Application\Model\User\Role;

use Application\Model\Row as Prototype;
use Zend\Stdlib\Hydrator\Strategy\ClosureStrategy;

class Row extends Prototype
{
    protected $_hydrator;
    
    public function getHydrator()
    {
        if($this->_hydrator === null){
            $config = $this->_sm->get('config');
            $permissions = $config['acl']['permissionLabels'];
        
            $this->_hydrator = new \Application\Model\HydratorObjectProperty();
            $closureStrategy = new ClosureStrategy(function($value){
                return $value;
            }, function($value, $data) use($permissions){
                $permissionValues = array();
                foreach($permissions AS $item){
                    foreach($item['childs'] AS $permission){
                        $itemValue = false;
                        if(!isset($permission['resource'])){
                            continue;
                        }
                        if(isset($data[$permission['resource']])){
                            $itemValue = $data[$permission['resource']];
                        }
                        $permissionValues[$permission['resource']] = ($itemValue == 'allow')?'allow':'deny';
                    }
                }
                return json_encode($permissionValues);
            });
            $this->_hydrator->addStrategy('permissions', $closureStrategy);
        }
        return $this->_hydrator;
    }
    
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new \Application\InputFilter\InputFilter;

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
    
    public function getPermissions()
    {
        return json_decode($this->permissions, true);
    }
    
    public function __clone()
    {
        unset($this->_data['id']);
    }
}