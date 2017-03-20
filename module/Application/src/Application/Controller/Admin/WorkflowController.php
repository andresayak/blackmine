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

class WorkflowController extends AbstractController
{
    public function init()
    {
        $this->layout('layout/admin');
    }
    
    public function indexAction()
    {
        $this->init();
    }
}
