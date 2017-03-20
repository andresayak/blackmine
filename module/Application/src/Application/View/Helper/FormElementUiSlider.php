<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class FormElementUiSlider extends AbstractHelper
{
    public function __invoke($element, $options = array()) 
    {
        $value = $element->getValue();
        if($options['type'] == 'percent'){
            $valueSlider = (int)($value*100);
        }else{
            $valueSlider = $value;
        }
        $elementName = $element->getName();
        $suffix = '%';
        $id = 'input-'.$elementName;
        $str ='<script>
                $(function() {
                    $( "#slider-'.$elementName.'" ).slider({
                        value: '.$valueSlider.',
                        max: '.$options['max'].',
                        min: '.$options['min'].',
                        orientation: "horizontal",
                        range: "min",
                        animate: true,
                        slide: function( event, ui ) {
                            $("#label-'.$elementName.'").text( ui.value +\''.$suffix.'\');
                            $("#'.$id.'").val(ui.value/100);
                        }
                    });
                });
            </script>'
                . '<div id="slider-'.$elementName.'"></div>'
                . '<span id="label-'.$elementName.'" class="slider-percent-value">'.$valueSlider.$suffix.'</span>'
                . $this->getView()->formElement($element->setAttribute('id', $id));
        return $str;
        
    }
}
