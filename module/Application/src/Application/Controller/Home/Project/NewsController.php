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

class NewsController extends AbstractController
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
    
    public function addAction()
    {
        $project_id = $this->params()->fromRoute('project_id');
        $table = $this->getServiceLocator()->get('Project\Table');
        $projectRow = $table->fetchBy('id', $project_id);
        if(!$projectRow){
            return $this->redirect()->toRoute('projects');
        }
        
        $this->init();
        $form = new \Application\Form\Project\News;
        $form->init();
        return array(
            'form' =>  $form,
            'projectRow'   =>$projectRow
        );
    }
}
