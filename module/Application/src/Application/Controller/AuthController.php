<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Application\Form;
use Application\Form\InputFilter;

class AuthController extends AbstractController
{
    public function init()
    {
        $this->layout('layout/auth');
    }
    
    public function indexAction()
    {
        $this->init();
        $captcha = $this->getServiceLocator()->get('ReCaptchaService');
        $config = $this->getServiceLocator()->get('config');
        $captchaStatus = $config['recaptcha']['enable'];
        
        $authService = $this->getServiceLocator()->get('Auth\Service');
        if($authService->getUserRow()){
            return;
        }
        $viewModel = new ViewModel(array(
            'captchaKey'    =>  $config['recaptcha']['public_key'],
            'captchaStatus' =>  $captchaStatus,
            'captcha'       =>  $captcha,
            'captchaError'  =>  false,
        ));
        $filter = new Form\InputFilter\User\Login($this->getServiceLocator());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $resCaptchaStatus = true;
            if($captchaStatus){
                $resCaptchaStatus = false;
                if(isset($_POST['g-recaptcha-response'])){
                    $resCaptcha=$_POST['g-recaptcha-response'];
                    $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$config['recaptcha']['private_key']
                        ."&response=".$resCaptcha
                        ."&remoteip=".IP_ADDRESS);
                    $responseKeys = json_decode($response,true);
                    if(!($resCaptchaStatus = $responseKeys['success']) 
                        || $responseKeys['hostname']!=$_SERVER['SERVER_NAME']
                    ){
                        $viewModel->setVariable('captchaError', true);
                    }
                }
            }
            $filter->setData($request->getPost()->toArray());
            if($resCaptchaStatus){
                if ($filter->isValid()) {
                    $authService->authenticate($filter->getUserRow()->id);
                    return $this->redirect()->toRoute('home', array(
                    ));
                }else{
                    $this->addMessage('Login or/and password invalid', 'error');
                    return $this->redirect()->toRoute('default', array(
                        'controller'    =>  'auth'
                    ));
                }
            }
        }
        $form = new Form\Login();
        $form->setInputFilter($filter);
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }
    
    public function logoutAction()
    {
        $this->init();
        $service = $this->getServiceLocator()->get('Auth\Service');
        $service->logout();
        $this->addMessage('You are logged out', 'success');
        return $this->redirect()->toRoute('default');
    }
}
