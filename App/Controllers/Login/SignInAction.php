<?php

namespace App\Controllers\Login;

use System\Core\AbstractModel;
use System\Core\AbstractView;
use App\Controllers\AbstractAction;
use System\Core\AbstractController;

class SignInAction extends AbstractAction
{
    public function __construct($model, $view, $session, $data)
    {
        parent::__construct($model, $view, $session, $data);
    }
    
    public function execute()
    {
        if ($this->verify()) {
            return $this->renderErrorMesage('Debe completar usuario y contraseña.'); 
        }
        $data = $this->model->signIn($this->data['user']);  
        if ($data['TotalRecordCount'] === 0) {
            return $this->renderErrorMesage("El usuario {$this->data['user']} no existe.");  
        }
        $result = $data['Records'][0];
        if (password_verify($this->data['pass'], $result['pass']) === false) {
            return $this->renderErrorMesage('Verificar usuario y contraseña.'); 
        }
        
        $this->addSessionValues($result);
        header("location: " . HOME . "/AdminHome");
    } 
    
    private function addSessionValues($result)
    {
        $this->session->init();
        $this->session->add('user', $result['nick']);
        $this->session->add('user_id', $result['id']);  
        $this->session->add('full_name', $result['full_name']);
        $this->session->add('user_ip', $this->getUserIpAddr());
        $this->session->add('start', time()); 
        $this->session->add('language', LANG_DEFAULT);
    }
    
    public function verify()
    {
        return empty($this->data['user']) OR empty($this->data['pass']);   
    }
    
    public function renderErrorMesage($message)
    {
        $this->render(__CLASS__, array('error_message' => $message));
    }
    
    public function getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
  
}