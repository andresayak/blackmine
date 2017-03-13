<?php

namespace Application\Controller\Home\Project;

use Application\Controller\AbstractController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Application\Form;
use Zend\Paginator\Paginator;
use Application\Form\InputFilter;

class IndexController extends AbstractController
{
    public function init(){
        $this->layout('layout/admin');
    }
    
    public function indexAction()
    {
        $project_id = $this->params()->fromRoute('project_id');
        $table = $this->getServiceLocator()->get('Project\Table');
        $projectRow = $table->fetchBy('id', $project_id);
        if(!$projectRow){
            return $this->redirect()->toRoute('projects');
        }
        $this->init();
        return array(
            'projectRow'   =>$projectRow
        );
    }
    
    public function viewAction()
    {
        $project_id = $this->params()->fromRoute('project_id');
        $table = $this->getServiceLocator()->get('Project\Table');
        $projectRow = $table->fetchBy('id', $project_id);
        if(!$projectRow){
            return $this->redirect()->toRoute('projects');
        }
        $this->init();
        return array(
            'projectRow'   =>$projectRow
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
