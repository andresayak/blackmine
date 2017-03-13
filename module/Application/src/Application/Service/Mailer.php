<?php

namespace Ap\Service;

use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver;
use Zend\Mail\Message;
use Zend\Mail;
use Zend\Mail\Transport\SmtpOptions;

class Mailer 
{
    protected $_transport, $_renderer, $_storage;
    protected $_options = array(
        'status'        =>  false,
        'site_email'    =>  null,
        'site_name'     =>  null,
        'script_paths'  =>  array()
    );
    
    public function __construct($options)
    {
        $this->_options = array_merge($this->_options, $options);
        return $this;
    }
    
    public function getStorage()
    {
        return $this->_storage;
    }
    
    public function setStorage($storage)
    {
        $this->_storage = $storage;
        return $this;     
    }
    
    public function getOption($var)
    {
        if($this->_options[$var] === null){
            throw new \Exception('Option not set ['.$var.']');
        }
        return $this->_options[$var];
    }
    
    public function setOption($var, $value)
    {
        $this->_options[$var] = $value;
        return $this;
    }
    
    public function setScriptPaths($paths)
    {
        foreach($paths AS $path){
            if(!is_dir($path)){
                throw new \Exception('Path invalid ['.$path.']');
            }
        }
        $this->setOption('script_paths', $paths);
        return $this;
    }
    
    public function getTransport()
    {
        if($this->_transport === null){
            if(isset($this->_options['smtp'])){
                $this->_transport = new Mail\Transport\Smtp(new SmtpOptions($this->_options['smtp']));
            }else{
                $this->_transport = new Mail\Transport\Sendmail;
            }
        }
        return $this->_transport;
    }
    
    public function getRenderer()
    {
        if($this->_renderer === null){
            $this->_renderer = new PhpRenderer();
            $resolver = new Resolver\AggregateResolver();
            $this->_renderer->setResolver($resolver);
            $stack = new Resolver\TemplatePathStack(array(
                'script_paths' => $this->_options['script_paths']));
            $resolver->attach($stack);
        }
        return $this->_renderer;
    }
    
    public function setRenderer(PhpRenderer $renderer)
    {
        $this->_renderer = $renderer;
        return $this;
    }
    
    function run($limit = false)
    {
        $n = 0;
        while($json = $this->getStorage()->lPop('emailList')){
            $data = json_decode($json, true);
            if(is_array($data['message'])){
                $viewModel = new ViewModel($data['message']);
                $viewModel->setTemplate($data['template']);
            }else{
                $viewModel = $data['message'];
            }
            $message = new Message();
            $message->setSubject($data['subject']);
            if(is_array($data['email'])){
                foreach($data['email'] As $email){
                    $message->addTo($email);
                }
            }else{
                $message->addTo($data['email']);
            }
            $this->sendNow($viewModel, $message);
            if($limit == ++$n){
                break;
            }
        }
        return $n;
    }
    
    public function send($emails, $template, $subject, $arguments, $force = null) 
    {
        if($emails == 'manager'){
            $emails = $this->getOption('manager_emails');
        }
        if($emails == 'admin'){
            $emails = $this->getOption('admin_emails');
        }
        $force = ($force === null)?!$this->getOption('enable_background'):$force;
        if($force){
            $viewModel = new ViewModel($arguments);
            $viewModel->setTemplate($template);
            $message = new Message();
            $message->setSubject($subject);
            if(is_array($emails)){
                foreach($emails As $email){
                    $message->addTo($email);
                }
            }else{
                $message->addTo($emails);
            }
            $this->sendNow($viewModel, $message);
        }else{
            $this->add($emails, $template, $subject, $arguments);
        }
        return $this;
    }

    public function add($email, $template, $subject, $arguments)
    {
        $this->getStorage()->lPush('emailList', json_encode(array(
            'email'     =>  $email,
            'template'  =>  $template,
            'subject'   =>  $subject,
            'message'   =>  $arguments
        )));
        return $this;
    }
    
    function sendNow($viewModel, Message $message) 
    {
        if($viewModel instanceof ViewModel){
            $html = $this->getRenderer()->render($viewModel);
            $bodyMessage = new \Zend\Mime\Part($html);
            $bodyMessage->type = 'text/html';
            $bodyPart = new \Zend\Mime\Message();
            $bodyPart->setParts(array($bodyMessage));
        }else{
            $bodyPart = $viewModel;
        }
        $siteEmail = $this->getOption('site_email');
        $siteName = $this->getOption('site_name');
        $message->setSender($siteEmail, $siteName)
                ->addFrom($siteEmail, $siteName);
        $message->setBody($bodyPart);
        $message->setEncoding('UTF-8');

        $this->getTransport()->send($message);
    }
}