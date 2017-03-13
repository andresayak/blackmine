<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class TreeCheckbox extends AbstractHelper
{
    public function __invoke($aclConfig, $form, $partial)
    {
        return $this->render($aclConfig, $form, $partial);
    }
    public function render($aclConfig, $form, $partial, $parent=null, $level = 0)
    {
        $str = '';
        foreach($aclConfig AS $item){
            $code = (($parent)?$parent.'_':'').$item['resource'];
            if($form->has($code)){
                $children = '';
                if(isset($item['children'])){
                    $children = $this->render($item['children'], $form, $partial, $code, $level+1);
                }
                $str.= $this->getView()->partial($partial, array(
                    'form'  =>  $form,
                    'code'  =>  $code,
                    'level' =>  $level,
                    'children' =>  $children
                ));
            }
        }
        return $str;
    }
}