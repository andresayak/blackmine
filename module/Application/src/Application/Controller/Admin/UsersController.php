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

class UsersController extends AbstractController
{
    public function init()
    {
        $this->layout('layout/admin');
    }
    
    public function indexAction()
    {
        $this->init();
        $tableGateway = $this->getServiceLocator()->get('User\Parent\Table')->getTableGateway();
        $adapter = new \Application\Paginator\Adapter\DbTableGateway($tableGateway, array(
            'parent_id'   =>  $this->getAuthUser()->id
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
        $this->init();
        
        $userRow = $this->getTable('User')->createRow();
        $form = new Form\Admin\User($this->getServiceLocator());
        $form->init();
        $form->bind($userRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            if($this->params()->fromQuery('mode') == 'invite' && isset($postData['email'])){
                if($existUserRow = $this->getTable('User')->fetchBy('email', $postData['email'])){
                    if($existUserRow->id == $this->getAuthUser()->id){
                        $this->addMessage('It is your account', 'error');
                        return $this->redirect()->toRoute('admin', array(
                            'controller'    =>  'users',
                            'action'    =>  'add'
                        ));
                    }
                    $parentRow = $this->getTable('User\Parent')->createRow(array(
                        'parent_id' =>  $this->getAuthUser()->id,
                        'user_id'   =>  $existUserRow->id,
                        'time_add'  =>  time()
                    ));
                    $parentRow->save();
                    $this->addMessage('User #'.$userRow->id.' added', 'success');
                    return $this->redirect()->toRoute('admin', array(
                        'controller'    =>  'users',
                        'action'        =>  isset($postData['continue'])?'add':null,
                    ));
                }
            }
            $form->setInputFilter($userRow->getInputFilter());
            $form->setData($postData);
            if ($form->isValid()) {
                $userRow->setFromArray(array(
                    'time_add'  =>  time(),
                ));
                $userRow->save();
                
                $parentRow = $this->getTable('User\Parent')->createRow(array(
                    'parent_id' =>  $this->getAuthUser()->id,
                    'user_id'   =>  $userRow->id,
                    'time_add'  =>  time()
                ));
                $parentRow->save();
                $this->addMessage('User #'.$userRow->id.' created.', 'success');
                return $this->redirect()->toRoute('admin', array(
                    'controller'    =>  'users',
                    'action'        =>  isset($postData['continue'])?'add':null,
                ));
            }
        }
        return array(
            'form'          =>  $form,
            'erros' =>  $form->getInputFilter()->getMessages()
        );
    }
    
    public function editAction()
    {
        $this->init();
        if(!$parentRow = $this->getRow('User\Parent')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        
        $form = new Form\Admin\User($this->getServiceLocator());
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
            'form'          =>  $form
        );
    }
    
    public function removeAction()
    {
        $this->init();
        if(!$parentRow = $this->getRow('User\Parent')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        $parentRow->delete();
        $this->addMessage('Saved', 'success');
        return $this->_redirect();
    }
}
