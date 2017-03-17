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

class TasksController extends AbstractController
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
        
        $tableGateway = $this->getServiceLocator()->get('Project\Task\Table')->getTableGateway();
        $adapter = new \Application\Paginator\Adapter\DbTableGateway($tableGateway, array(
            'project_id'    =>  $projectRow->id
        ), 'time_update DESC');
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))
            ->setItemCountPerPage(100);
        return array(
            'paginator' => $paginator,
            'projectRow'   =>$projectRow
        );
    }
    
    public function addAction()
    {
        $project_id = $this->params()->fromRoute('project_id');
        $table = $this->getServiceLocator()->get('Project\Table');
        $projectRow = $table->fetchBy('id', $project_id);
        if(!$projectRow){
            return $this->redirect()->toRoute('projects');
        }
        
        $taskRow = $this->getServiceLocator()->get('Project\Task\Table')->createRow(array(
            'time_add'  =>  time(),
            'time_update'   =>  time()
        ))->setAuthRow($this->getAuthUser())
            ->setProjectRow($projectRow);
        $this->init();
        $form = new \Application\Form\Project\Task;
        $form->bind($taskRow);
        $form->init();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $form->setInputFilter($taskRow->getInputFilter());
            $form->setData($postData);
            if ($form->isValid()) {
                $taskRow->save();
                $this->addMessage('Task #'.$taskRow->id.' created.', 'success');
                return $this->redirect()->toRoute('project-view', array(
                    'controller'    =>  'tasks',
                    'action'        =>  isset($postData['continue'])?'add':null,
                    'project_id'    =>  $projectRow->id,
                ));
            }
        }
        return array(
            'form' =>  $form,
            'projectRow'   => $projectRow
        );
    }
    
    public function viewAction()
    {
        $project_id = $this->params()->fromRoute('project_id');
        $table = $this->getServiceLocator()->get('Project\Table');
        if(!$project_id || !$projectRow = $table->fetchBy('id', $project_id)){
            return $this->redirect()->toRoute('projects');
        }
        
        $task_id = $this->params()->fromQuery('id', false);
        $taskTable = $this->getServiceLocator()->get('Project\Task\Table');
        if(!$task_id || !$taskRow = $taskTable->fetchBy('id', $task_id)){
            $this->addMessage('Task not found', 'error');
            return $this->redirect()->toRoute('projects');
        }
        $this->init();
        
        return array(
            'taskRow'  =>  $taskRow,
            'projectRow'   =>$projectRow
        );
    }
}
