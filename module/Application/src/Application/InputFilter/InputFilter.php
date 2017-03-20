<?php

namespace Application\InputFilter;

use Application\Validator\ValidatorChain;
use Application\Filter\FilterChain;

class InputFilter extends \Zend\InputFilter\InputFilter
{
    protected $_sm;
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setSm($sm)
    {
        $this->_sm = $sm;
        return $this;
    }
    public function getFactory()
    {
        if (null === $this->factory) {
            parent::getFactory();
            $validatorChain = new ValidatorChain;
            $validatorChain->setFilter($this);
            
            $filterChain = new FilterChain;
            $filterChain->setFilter($this);
            
            $this->getFactory()->setDefaultValidatorChain($validatorChain);
            $this->getFactory()->setDefaultFilterChain($filterChain);
        }
        return $this->factory;
    }
}