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

class ReportsController extends AbstractController
{
    public function init(){
        $this->layout('layout/admin');
    }
    public function indexAction()
    {
        $this->init();
        $userRowset = $this->getTable('User')->getRowset();
        $statusRowset = $this->getTable('Status')->getRowset();
        $form = new Form\Search\Report();
        $form->setUserRowset($userRowset);
        $form->setStatusRowset($statusRowset);
        $form->init();
        $defaultStatusId = null;
        foreach($statusRowset->getItems() AS $statusRow){
            if($statusRow->is_close == 'yes'){
                $defaultStatusId = $statusRow->id;
            }
        }
        $defaultData = array(
            'status_id' =>  $defaultStatusId,
            'group'     =>  'user_id',
            'date_from' =>  date('Y-m-d', strtotime('-1 month')),
            'date_to'   =>  date('Y-m-d', strtotime('+1 day'))
        );
        $data = array_merge($defaultData, $this->params()->fromQuery());
        $form->setData($data);
        $form->isValid();
        
        $data = $form->getData();
        if(!isset($data['group']) || !$data['group']){
            $data['group'] = 'user_id';
        }
        $table = $this->getTable('Order');
        $select = $table->getTableGateway()->getSql()->select();
        /*$select->columns(array(
            $data['group'], 'order_count'  =>  new \Zend\Db\Sql\Expression('COUNT(*)'),
            'price_work'  =>  new \Zend\Db\Sql\Expression('SUM(price_work)')
        ));
        $select->join('user', 'user.id = user_id', array(
            'user_name'=>'name',
            'user_commission'=>'commission',
        ));*/
        $a = strptime($data['date_from'], '%Y-%m-%d');
        $dateFrom = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
        
        $a = strptime($data['date_to'], '%Y-%m-%d');
        $dateTo = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);

        $user_id =  $form->get('user_id')->getValue();
        $where = array(
            'user_id'   =>  $user_id,
            'time_add >= ?'   =>  $dateFrom,
            'time_add < ?'   =>  $dateTo,
        );
        
        if($data['status_id']){
            $where['status_id'] = $data['status_id'];
        }
        $select->where($where);
        $total = array(
            'sum'   =>  0,
            'payout' =>  0
        );
        $userRow = false;
        $rowset = array();
        if($user_id && $userRow = $userRowset->getBy('id', $user_id)){
            //$select->group($data['group']);//$data['group']);
            $result = $table->getTableGateway()->selectWith($select);
            foreach($result AS $item){
                $rowset[] = $item;
            }
            foreach ($rowset AS $item){
                $total['sum']+= $item->price_work;
                $total['payout']+= $item->price_work * $item->user_commission/100;
            }
        }
        return array(
            'userRow'   =>  $userRow,
            'total'     =>  $total,
            'data'      =>  $data,
            'rowset'    =>  $rowset,
            'form'      =>  $form,
        );
    }
}
