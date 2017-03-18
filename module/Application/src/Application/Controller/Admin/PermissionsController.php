<?php

namespace Application\Controller\Admin;

use Application\Controller\AbstractController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Application\Form;
use Zend\Paginator\Paginator;
use Application\Form\InputFilter;

class PermissionsController extends AbstractController
{
    public function init()
    {
        $this->layout('layout/admin');
    }
    
    public function indexAction()
    {
        $this->init();
        $tableGateway = $this->getServiceLocator()->get('User\Role\Table')->getTableGateway();
        $adapter = new \Application\Paginator\Adapter\DbTableGateway($tableGateway, array(
            'user_id'   =>  $this->getAuthUser()->id
        ), 'id DESC');
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(100);
        
        return array(
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $roleTable = $this->getTable('User\Role');
        $copyRow = false;
        if($copy_id = $this->params()->fromQuery('copy', false))
            $copyRow = $roleTable->fetchBy('id', $copy_id);
        
        $user_id = $this->getAuthUser()->id;
        $this->init();
        if($copyRow){
            $roleRow = clone $copyRow;
        }else{
            $roleRow = $roleTable->createRow();
        }
        $form = new Form\Admin\Role($this->getServiceLocator());
        $form->init();
        $form->bind($roleRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($roleRow->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $roleRow->setFromArray(array(
                    'user_id'       =>  $user_id,
                    'time_add'      =>  time(),
                ));
                $roleRow->save();
                
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array(
            'form'          =>  $form
        );
    }
    
    public function editAction()
    {
        $user_id = $this->getAuthUser()->id;
        $this->init();
        if(!$roleRow = $this->getRow('User\Role')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        
        $form = new Form\Admin\Role($this->getServiceLocator());
        $form->init();
        $form->bind($roleRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($roleRow->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $roleRow->setFromArray(array(
                    'user_id'       =>  $user_id,
                    'time_add'      =>  time(),
                ));
                $roleRow->save();
                
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array(
            'form'          =>  $form
        );
    }
    
    public function removeAction()
    {
        $user_id = $this->getAuthUser()->id;
        $this->init();
        if(!$roleRow = $this->getRow('User\Role')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        $roleRow->delete();
        $this->addMessage('Saved', 'success');
        return $this->_redirect();
    }
}
