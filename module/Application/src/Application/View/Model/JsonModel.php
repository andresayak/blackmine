<?php

namespace Application\View\Model;

use Traversable;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;

class JsonModel extends \Zend\View\Model\JsonModel
{
    protected $_options;
    
    public function setJsonOptions($options)
    {
        $this->_options = $options;
        return $this->_options;
    }
    
    public function serialize()
    {
        $variables = $this->getVariables();
        if ($variables instanceof Traversable) {
            $variables = ArrayUtils::iteratorToArray($variables);
        }

        if (null !== $this->jsonpCallback) {
            return $this->jsonpCallback.'('.Json::encode($variables).');';
        }
        return json_encode($variables, $this->_options);
    }
}
