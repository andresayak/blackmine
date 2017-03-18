<?php

namespace Application\Model\User;

use Application\Model\Row as Prototype;
use Zend\Stdlib\Hydrator\Strategy\ClosureStrategy;

class Row extends Prototype
{
    protected $_password, $_customFieldRowset;
    protected $_hydrator;
    static $roles = array(
    );
    
    public function getCustomFieldRowset()
    {
        if($this->_customFieldRowset === null){
            $this->_customFieldRowset = $this->getSm()
                ->get('User\CustomField\Table')->fetchAllBy('user_id', $this->id);
        }
        return $this->_customFieldRowset;
    }
    
    public function getRoleName()
    {
        return self::$roles[$this->role];
    }
    
    public function getHydrator()
    {
        if($this->_hydrator === null){
            $this->_hydrator = new \Application\Model\User\HydratorObjectProperty();
            $closureStrategy = new ClosureStrategy(function($value){
                return '';
            }, function($value, $data){
                if(!$this->id || (isset($data['password_change']) && $data['password_change'])){
                    return $this->getSm()->get('Auth\Service')->passwordHash($value);
                }
                return $this->password;
            });
            $this->_hydrator->addStrategy('password', $closureStrategy);
        }
        return $this->_hydrator;
    }
    
    public function getInputFilter()
    {
        if($this->_inputFilter === null){
            $inputFilter = new \Application\InputFilter\InputFilter;
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    
                ),
                'validators' => array(
                    array('name' => 'EmailAddress'),
                    new \Zend\Validator\Db\NoRecordExists(
                        array(
                            'adapter'   =>  $this->getTable()->getTableGateway()->getAdapter(),
                            'table'     =>  $this->getTable()->getName(),
                            'field'     =>  'email',
                            'exclude'   =>  (($this->id)?array(
                                'field' => 'id',
                                'value' => $this->id
                            ):null)
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'password_again',
                'required' => true,
                'validators' => array(
                    array(
                        'name'  =>  'passwordAgain'
                    )
                )
            ));
            
            
            $inputFilter->add(array(
                'name' => 'password_change',
                'required' => false,
                'validators' => array(
                )
            ));
            $inputFilter->add(array(
                'name' => 'ban_status',
                'required' => false,
            ));

            $this->_inputFilter = $inputFilter;
        }
        return $this->_inputFilter;
    }
}