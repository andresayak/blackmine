<?php

namespace Application\Controller\Home;

use Application\Controller\AbstractController;
use Application\Form;

class CustomerController extends AbstractController
{
    protected function _redirect($action = 'index', $query = array(), $route = 'customers')
    {
        return parent::_redirect($action, $query, $route);
    }
    
    public function init(){
        $this->layout('layout/admin');
    }
    public function indexAction()
    {
        return $this->_redirect();
    }
    
    public function addAction()
    {
        $this->init();
        $userRow = $this->getTable('Customer')->createRow();
        $form = new Form\Customer;
        $form->init();
        $form->bind($userRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($userRow->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userRow->save();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array(
            'userRow'   =>  $userRow,
            'form'      =>  $form);
    }
    
    public function editAction()
    {
        $this->init();
        if(!$userRow = $this->getRow('Customer')){
            $this->addError('Not found', 'error');
            return $this->_redirect();
        }
        $form = new Form\Customer;
        $form->init();
        $form->bind($userRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($userRow->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userRow->save();
                $this->addMessage('Saved', 'success');
                return $this->_redirect();
            }
        }
        return array(
            'userRow'   =>  $userRow,
            'form'      =>  $form
        );
    }
    
    public function viewAction()
    {
        $this->init();
        if(!$userRow = $this->getRow('Customer')){
            $this->addError('Not found', 'error');
            return $this->_redirect();
        }
        return array(
            'userRow'     =>  $userRow,
        );
    }
    
    public function removeAction()
    {
        $this->init();
        if(!$userRow = $this->getRow('Customer')){
            $this->addError('Not found', 'error');
            return $this->_redirect();
        }
        $userRow->delete();
        $this->addMessage('Removed', 'success');
        return $this->_redirect();
    }
}
