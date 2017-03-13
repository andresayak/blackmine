<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Application\Form;

class AdminController extends AbstractController
{
    public function indexAction()
    {
        $this->layout('layout/admin');
    }
}
