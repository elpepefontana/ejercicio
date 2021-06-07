<?php

namespace App\Controllers\Login;

use System\Core\AbstractModel;
use System\Core\AbstractView;
use App\Controllers\AbstractAction;

defined('BASEPATH') or exit('No se permite acceso directo');

class LoginController extends \System\Core\AbstractController 
{
    public function __construct(AbstractAction $action) 
    {
        parent::__construct();
        $this->action = $action;
    }
    
    public function exec($params)
    {
        //$this->verifySession();
        
        $this->action->setData($params);
        $this->action->execute();
    }
    
    public function signIn($params)
    {
        if ($this->verify($params)) {
            return $this->renderErrorMesage('Debe completar usuario y contraseña.'); 
        }
        $data = $this->model->signIn($params['user']);  
        if ($data['TotalRecordCount'] === 0) {
            return $this->renderErrorMesage("El usuario {$params['user']} no existe.");  
        }
        $result = $data['Records'][0];
        if (password_verify($params['pass'], $result['pass']) === false) {
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
        $this->session->setImagesAccess(
            $this->session->get('user_ip'), 
            $this->session->get('user_id')
        );
    }
    
    public function verify($params)
    {
        return empty($params['user']) OR empty($params['pass']);   
    }
    
    public function renderErrorMesage($message)
    {
        $params = array('error_message' => $message);
        $this->render(__CLASS__, $params);
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

    // VIEW RENDER FUNCTION MARKER -- DONT ERASE -- MARCADOR DE FUNCIONES DE RENDERIZADO DE VISTAS -- NO BORRAR //
    
}
