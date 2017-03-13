<?php

namespace Application\Navigation\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeftpanelFactory implements FactoryInterface
{    
    public function createService(ServiceLocatorInterface $sm)
    {
        $navigation = array();
        $routeMatch = $sm->get('Application')->getMvcEvent()->getRouteMatch();
        $config = $sm->get('config');
        $leftMenuConfig = $config['leftmenu'];
        foreach($leftMenuConfig AS $item){
            if(isset($item['routeRegex']) && $item['routeRegex']){
                if(preg_match($item['routeRegex'], $routeMatch->getMatchedRouteName())) {
                    if(isset($config['navigation'][$item['menuName']])){
                        $navigation = $config['navigation'][$item['menuName']];
                    }
                    if(isset($item['pageIndex']) && isset($navigation[$item['pageIndex']])){
                        $navigation = $navigation[$item['pageIndex']]['pages'];
                    }
                    if(isset($item['agruments'])){
                        $agruments = array();
                        if(is_string($item['agruments'])){
                            $agruments[$item['agruments']] = $routeMatch->getParam($item['agruments']);
                        }
                        foreach($navigation AS $index=>$page){
                            $navigation[$index]['params'] = array_merge($page['params'], $agruments);
                        }
                    }
                    break;
                }
            }
        }
        $factory = new \Zend\Navigation\Service\ConstructedNavigationFactory($navigation);
        return $factory->createService($sm);
    }
    
    protected function getPagesFromConfig($config = null)
    {
        
    }
}