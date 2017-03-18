<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class Cms extends AbstractHelper
{
    protected $_service;
    public function __construct($service)
    {
        $this->_service = $service;
        return $this;
    }
    
    public function getService()
    {
        return $this->_service;
    }
    
    public function __invoke()
    {
        $arguments = func_get_args();
        $config = $this->getService()->get('config');
        if(count($arguments) == 2){
            if($arguments[0] == 'config'){
                $path = explode('.', $arguments[1]);
                $n = 0;
                $count = count($path);
                $pref = '$config';
                $value = '';
                $breakStatus = false;
                foreach($path AS $item){
                    $pref.='[\''.$item.'\']';
                    $n++;
                    if($count == $n){
                        eval('$value = (isset('.$pref.'))?'.$pref.':[];');
                    }else{
                        eval('$breakStatus = (!isset('.$pref.'));');
                    }
                    if($breakStatus){
                       break; 
                    }
                }
                return $value;
            }
        }
        
        var_dump($arguments);exit;
    }
}