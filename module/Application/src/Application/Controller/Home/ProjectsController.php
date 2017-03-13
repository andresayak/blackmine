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
    
    public function addAction()
    {
        $user_id = $this->getAuthUser()->id;
        $this->init();
        $projectRow = $this->getTable('Project')
            ->createRow();
        $form = new Form\Project;
        $form->init();
        $form->bind($projectRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($projectRow->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $projectRow->setFromArray(array(
                    'auth_id'     =>  $user_id,
                    'time_add'      =>  time(),
                ));
                $projectRow->save();
                
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array(
            'projectRow'    =>  $projectRow,
            'form'          =>  $form
        );
    }
    
    public function editAction()
    {
        $this->init();
        if(!$orderRow = $this->getRow('Order')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        $oldUserId = $orderRow->user_id;
        $customerRowset = $this->getTable('Customer')->getRowset();
        $form = new Form\Order;
        $form->setCustomerRowset($customerRowset);
        $form->setUserRowset($this->getTable('User')->getRowset());
        $form->setCategoryRowset($this->getTable('Category')->getRowset());
        $form->init();
        $form->bind($orderRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($orderRow->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $orderRow->setFromArray(array(
                    'time_update'      =>  time(),
                    'price'         =>  $orderRow->price_work + $orderRow->price_detail,
                ));
                $orderRow->save();
                
                if($orderRow->user_id!=$oldUserId){
                    $commentRow = $this->getTable('Order\History')->createRow(array(
                        'user_id'   =>  $orderRow->user_id,
                    ));
                    $this->_setUser($orderRow, $commentRow);
                }
                
                $this->addMessage('Збережено');
                return $this->_redirect();
            }
        }
        return array(
            'customerRowset'   =>  $customerRowset,
            'orderRow'  =>  $orderRow,
            'form' => $form
        );
    }
}
