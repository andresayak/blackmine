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

class IndexController extends AbstractController
{
    public function init(){
        $this->layout('layout/admin');
    }
    public function indexAction()
    {
        $this->init();
        $form = new Form\Search\Customer();
        $form->init();
        $form->setData($this->params()->fromQuery());
        $form->isValid();
        $tableGateway = $this->getServiceLocator()->get('Customer\Table')->getTableGateway();
        $adapter = new \Application\Paginator\Adapter\DbTableGateway($tableGateway, $form->getWhere(), 'id DESC');
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(100);
        
        return array(
            'form' =>  $form,
            'paginator' => $paginator
        );
    }
    
    public function accountAction()
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
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array(
            'userRow'  =>  $userRow,
            'form' => $form);
    }
}
