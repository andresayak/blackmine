<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
 
class ThemePath extends AbstractHelper
{
    protected $_sm;
    
    public function __construct($sm)
    {
        $this->_sm = $sm;
        return $this;
    }
    
    public function __invoke($uri)
    {
        $config = $this->_sm->get('config');
        return '/themes/'.$config['view_manager']['useThemeName'].$uri;
    }
}