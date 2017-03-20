<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Application\Service\Auth\Storage;

class Auth
{
    protected $_user_row, $_auth_service;
    protected $_table, $_sm;
    protected $_salt = '';
    
    public function __construct($table)
    {
        $this->_table = $table;
        return $this;
    }
    
    public function getSm()
    {
        return $this->_sm;
    }
    
    public function setSm($sm)
    {
        $this->_sm = $sm;
        return $this;
    }
    
    public function getUserRow()
    {
        if($this->_user_row === null) {
            if($this->getAuthService()->hasIdentity()){
                $data = $this->getAuthService()->getIdentity();
                if(is_array($data)){
                    $id = $data['user_id'];
                }else{
                    $id = $data;
                }
                $this->_user_row = $this->_table->fetchBy('id', $id);
                if(!$this->_user_row){
                    $this->logout();
                    $this->_user_row = false;
                }
                
            }else{
                $this->_user_row = false;
            }
        }
        return $this->_user_row;
    }
    
    public function reset()
    {
        $this->_user_row = null;
    }
    
    public function setUserRow($userRow)
    {
        $this->_user_row = $userRow;
        return $this;
    }
    
    public function setAuthService(AuthenticationService $auth_service)
    {
        $this->_auth_service = $auth_service;
    }
    
    public function getAuthService()
    {
        if($this->_auth_service === null){
            $session = $this->getSm()->get('Zend\Session\SessionManager');
            $this->_auth_service = new AuthenticationService(new Storage\Session(null, null, $session));
        }
        return $this->_auth_service;
    }
    
    public function isAuthenticate($email, $password)
    {
        $dbAdapter = $this->_table->getTableGateway()->getAdapter();
        $authAdapter = new AuthAdapter($dbAdapter,
            $this->_table->getName(), 
            'email', 
            'password', 
            'MD5(CONCAT("'.$this->_salt.'", ?)) AND ban_status = "off"');
        $authAdapter
            ->setIdentity($email)
            ->setCredential($password);
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
            $this->authenticate($authAdapter->getResultRowObject()->id);
            return true;
        }
        return false;
    }
    
    public function authenticate($id)
    {
        $storage = $this->getAuthService()->getStorage();
        $storage->write($id);
    }
    
    public function authenticateFromAdmin($user_id)
    {
        $id = $this->getAuthService()->getIdentity();
        if(is_array($id)){
            $id = $id['user_id'];
        }
        $data = array('user_id'=>$user_id, 'admin_id'=>$id);
        $storage = $this->getAuthService()->getStorage();
        $storage->write($data);
    }
    
    public function isFromAdmin()
    {
        $id = $this->getAuthService()->getIdentity();
        return (is_array($id) and isset($id['admin_id']));
    }
    
    public function logout()
    {
        $id = $this->getAuthService()->getIdentity();
        $this->getAuthService()->getStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
        if(is_array($id) and isset($id['admin_id'])){
            $this->authenticate($id['admin_id']);
        }
    }
    
    public function passwordHash($password)
    {
        return md5($this->_salt.$password);
    }
}