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

class SettingsController extends AbstractController
{
    public function init()
    {
        $this->layout('layout/admin');
    }
    
    public function indexAction()
    {
        $this->init();
    }
    
    public function displayAction()
    {
        $this->init();
    }
}
