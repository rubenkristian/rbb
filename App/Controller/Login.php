<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\User as User;

class Login extends Controller {
    
    public function __construct(){
        if($this->req->session->userdata('islogged')){
            return $this->res->redirect('');
        } else {
            $this->loadModel('User', new User());
        }
    }

    public function index(){
        if($this->req->getMethod() === 'GET')
            return $this->res->render('login-page', array('title'=>'Login'));
    }

    public function authentication(){
        if($this->req->getMethod() === 'POST'){
            $username = $this->req->Post('username');
            $password = $this->req->Post('password');
            $check = $this->User->checkAdmin($username, $password, 1, '');
            if($check['status']){
                $this->req->session->set_userdata(
                    array(
                        'islogged'=>true,
                        'id'=>$check['data']['id'],
                        'username'=>$check['data']['username']
                    )
                );
                return $this->res->redirect('');
            }else{
                return $this->res->redirect('login');
            }
        }else{
            return $this->res->redirect('login');
        }
    }
}