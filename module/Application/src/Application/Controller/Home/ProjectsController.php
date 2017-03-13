<?php

namespace Application\Controller\Home;

use Application\Controller\AbstractController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Application\Form;
use Zend\Paginator\Paginator;
use Application\Form\InputFilter;

class ProjectsController extends AbstractController
{
    public function init(){
        $this->layout('layout/admin');
    }
    
    public function getWhere($user_id, $userProject){
        $where = new \Zend\Db\Sql\Where();
        $items = array();
        foreach($userProject AS $userProjectRow){
            $items[] = $userProjectRow->project_id;
        }
        if(!count($items)){
            return array(
                'auth_id'   =>  $user_id
            );
        }else{
            $where->addPredicates(
                array(
                    new \Zend\Db\Sql\Predicate\In('id', $items),
                    'auth_id'   =>  $user_id
                ), 'OR'
            );
        }
        return $where;
    }
    
    public function indexAction()
    {
        $this->init();
        $user_id = $this->getAuthUser()->id;
        $userProject = $this->getServiceLocator()->get('Project\Member\Table')
            ->fetchAllBy('user_id', $user_id);
        $where = $this->getWhere($user_id, $userProject);
        $tableGateway = $this->getServiceLocator()->get('Project\Table')->getTableGateway();
        $adapter = new \Application\Paginator\Adapter\DbTableGateway($tableGateway, $where, 'id DESC');
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(100);
        return array(
            'paginator' => $paginator
        );
    }
}
