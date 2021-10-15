<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\User as User;

class Auth extends Controller{
    public function __construct(){
        $this->loadLib('csrf', 'CSRF');
        $this->loadLib('jwt', 'JWT');
        $this->loadModel('user', new User());
        $this->csrf->config('csrf');
    }

    public function randomtoken(){
        if($this->req->getMethod() === 'GET'){
            return $this->res->status(200)->json(
                array(
                    'token'=>$this->csrf->token()
                    )
                );
        }
    }
    
    // autentikasi account berdasarkan username dan password dan memberi token ke device
    public function authentication(){
        $json_message = array('status'=>false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST'){
            $username = $this->req->Post('username');
            $password = $this->req->Post('password');
            
            $result = $this->user->checkUser(
                $username, 
                $password);
                
            $status = $result['status'];
            
            if($status) {
                $data = $result['data'];
                $json_message = array(
                    'auth'=> true,
                    'token'=> $this->jwt->createToken($data, 'helloworld'),
                    'name'=> $data['name'],
                    'wa'=> $data['wa'],
                    'type'=> $data['type'],
                    'tac'=> $data['tac_agreement'] == 1,
                    'id'=> $data['id'],
                    'referal'=> $data['id_member'],
                    'occupation' => $data['occupation'],
                    'address' => $data['address']
                );
            }else{
                $json_message = array(
                    'auth'=> false,
                    'msg'=> $result['msg']
                );
            }
        }
        
        return $this->res->json($json_message);
    }

    public function login(){
        if($this->req->getMethod() === 'POST'){
            $username   = $this->req->Post('username');
            $password   = $this->req->Post('password');
            $csrftoken  = $this->req->getHeader('csrf-token');
            if(!$this->csrf->retoken($csrftoken)){
                return $this->res->status(401)->json(
                    array(
                        'error'=>array(
                            'msg'=>'Something went wrong'
                        )
                    )
                );
            }
            $checked = $this->user->checkUser($username, $password);
            if($checked['status']){
                $token = $this->jwt->encode($checked['data']);
                return $this->res->status(200)->json(
                    array(
                        'data'=>array(
                            'status'=>true, 
                            'token'=>$token
                        )
                    )
                );
            }else{
                return $this->res->status(401)->json(
                    array(
                        'error'=>array(
                            'msg'=>$checked['msg']
                        )
                    )
                );
            }
        }else{
            return $this->res->status(404)->render(
                'page404', 
                array(
                    'title'=>'404 NOT FOUND'));
        }
    }

    public function logout(){
        if($this->req->getMethod() === 'POST'){
            return $this->res->status(200)->json(
                array(
                    'data'=>array(
                        'status'=>true, 
                        'token'=>null
                    )
                )
            );
        }
    }
}