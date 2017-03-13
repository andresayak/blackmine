<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Application\Form;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        return array(
            'cssLayerName' =>  'index'
        );
        //$this->layout('layout/index');
    }
    
    public function pricingAction()
    {
        //$this->layout('layout/index');
    }
    
    public function faqAction()
    {
        //$this->layout('layout/index');
    }
    
    public function contactsAction()
    {
        $captcha = $this->getServiceLocator()->get('ReCaptchaService');
        $config = $this->getServiceLocator()->get('config');
        $captchaStatus = $config['recaptcha']['enable'];
        $viewModel = new ViewModel(array(
            'captcha' => $captcha,
            'captchaStatus' => $captchaStatus,
            'captchaKey' => $config['recaptcha']['public_key'],
            'captchaError' => false,
        ));
        $form = new Form\System\Feedback();
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
            $form->setData($request->getPost()->toArray());
            if ($resCaptchaStatus && $form->isValid()) {
                $data = $form->getData();
                $data['ip_address'] = IP_ADDRESS;
                $mailService = $this->getServiceLocator()->get('Mailer');
                $mailService->send(
                    'manager', 'email/system/feedback.phtml', 'Feedback', $data
                );
                $this->addMessage('Повідомлення успішно відправлено!', 'success');
                return $this->_redirect('index');
            }
        }
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('showPlaceHeader', true);
        return $viewModel;
    }
}
