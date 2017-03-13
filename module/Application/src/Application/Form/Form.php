<?php

namespace Application\Form;

use Zend\Form\FormInterface;

class Form extends \Zend\Form\Form
{
    public function filterPhone($query) 
    {
        $query = trim($query);
        $query = preg_replace(array(
            '/[^\d]/'
        ), '', $query);
        $query = preg_replace(array(
            '/^\+380/', '/^380/', '/^0/', '/[^\d]/'
        ), '', $query);
        return $query;
    }

    public function setObject($object)
    {
        parent::setObject($object);
        if($object instanceof \Ap\Model\Row){
            $key = $object->getTable()->getKey();
            if(!$object->isNotSave() && $this->has($key)){
                $this->get($key)->setAttribute('disabled', 'disabled');
                $object->getInputFilter()->remove($key);
            }
        }
        return $this;
    }
}