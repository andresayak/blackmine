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

class OrdersController extends AbstractController
{
    public function init(){
        $this->layout('layout/admin');
    }
    
    public function indexAction()
    {
        $this->init();
        $form = new Form\Search\Order();
        $form->setUserRowset($this->getTable('User')->getRowset());
        $form->init();
        $data = $this->params()->fromQuery();
        if($this->getAuthUser()->role == 'member'){
            $data['user_id'] = $this->getAuthUser()->id;
        }
        $form->setData($data);
        $form->isValid();
        $tableGateway = $this->getServiceLocator()->get('Order\Table')->getTableGateway();
        $adapter = new Application\Paginator\Adapter\DbTableGateway($tableGateway, $form->getWhere(), 'id DESC');
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(100);
        return array(
            'form'  =>  $form,
            'paginator' => $paginator
        );
    }
    
    public function addAction()
    {
        $this->init();
        $curtomerRow = false;
        $customer_id = $this->params()->fromQuery('customer_id', false);
        if($customer_id){
            $curtomerRow = $this->getTable('Customer')->fetchBy('id', $customer_id);
        }
        $statusRowset = $this->getTable('Status')->getRowset();
        $defaultStatus = $statusRowset->getBy('is_default', 'yes');
        $orderRow = $this->getTable('Order')->createRow(array(
            'price_detail'  =>  0,
            'price_work'  =>  0,
            'customer_name' =>  (($curtomerRow)?$curtomerRow->name:''),
            'customer_phone' =>  (($curtomerRow)?$curtomerRow->phone:''),
            'customer_skype' =>  (($curtomerRow)?$curtomerRow->skype:''),
            'customer_email' =>  (($curtomerRow)?$curtomerRow->email:''),
        ));
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
                    'status_id'     =>  $defaultStatus->id,
                    'time_add'      =>  time(),
                    'price'         =>  $orderRow->price_work + $orderRow->price_detail,
                    'customer_id'   =>  (($curtomerRow)?$curtomerRow->id:null),
                    'acceptor_id'   =>  $this->getAuthUser()->id
                ));
                $orderRow->save();
                
                if($orderRow->user_id){
                    $commentRow = $this->getTable('Order\History')->createRow(array(
                        'user_id'   =>  $orderRow->user_id,
                        'status_id' =>  $defaultStatus->id,
                    ));
                    $this->_setUser($orderRow, $commentRow);
                }
                
                $this->addMessage('Saved');
                return $this->_redirect();
            }
        }
        return array(
            'customerRowset'   =>  $customerRowset,
            'curtomerRow'  =>  $curtomerRow,
            'orderRow'  =>  $orderRow,
            'form' => $form);
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
    
    public function printAction()
    {
        $this->layout('layout/print');
        if(!$orderRow = $this->getRow('Order')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        return array(
            'orderRow'     =>  $orderRow,
        );
    }
    
    public function viewAction()
    {
        $this->init();
        if(!$orderRow = $this->getRow('Order')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        
        if($this->getAuthUser()->role == 'member' && $orderRow->user_id!=$this->getAuthUser()->id){
            $this->addError('Access denied');
            return $this->_redirect();
        }
        
        $commentRow = $this->getTable('Order\History')->createRow(array(
        ));
        $form = new Form\Order\History();
        $form->setUserRowset($this->getTable('User')->getRowset());
        $form->setStatusRowset($this->getTable('Status')->getRowset());
        $form->init();
        $form->bind($commentRow);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($commentRow->getInputFilter());
            $data = $request->getPost();
            if($this->getAuthUser()->role == 'member'){
                unset($data['user_id']);
            }
            $form->setData($data);
            if ($form->isValid()) {
                $this->_setUser($orderRow, $commentRow);
                $this->addMessage('Збережено');
                return $this->redirect()->toRoute('order', array(
                    'controller'=>  $this->params('__CONTROLLER__'),
                    'action'    =>  'view'
                ), array(
                    'query' =>  array(
                        'id'    =>  $orderRow->id
                    )
                ));
            }
        }
        return array(
            'form' =>  $form,
            'orderRow'     =>  $orderRow,
        );
    }
    
    protected function _setUser($orderRow, $commentRow)
    {
        $orderRow->setFromArray(array(
            'user_id' => (($commentRow->user_id) ? $commentRow->user_id : $orderRow->user_id),
            'status_id' => (($commentRow->status_id) ? $commentRow->status_id : $orderRow->status_id),
            'time_update' => time()
        ));
        if ($commentRow->status_id && $commentRow->getStatusRow()->is_close == 'yes') {
            $orderRow->time_end = time();
        }
        if ($commentRow->user_id && $commentRow->getUserRow()) {
            $orderRow->user_commission = $commentRow->getUserRow()->commission;
        }
        $orderRow->save();
        $commentRow->setFromArray(array(
            'order_id'  => $orderRow->id,
            'time_add'  => time(),
            'auth_id'   => $this->getAuthUser()->id,
        ));
        $commentRow->save();
    }

    public function removeAction()
    {
        $this->init();
        if(!$orderRow = $this->getRow('Order')){
            $this->addError('Not found');
            return $this->_redirect();
        }
        $orderRow->delete();
        $this->addMessage('Видалено');
        return $this->_redirect();
    }
}
