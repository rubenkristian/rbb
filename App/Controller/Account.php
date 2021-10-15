<?php
namespace App\Controller;

use System\Controller as Controller;
use App\Model\ForgotPassword as ForgotPassword;
use App\Model\User as User;
use App\Model\Wallet as Wallet;
use App\Model\Withdraw as Withdraw;

class Account extends Controller {
    public function __construct() {
        $this->loadModel(
            'user', 
            new User());
            
        $this->loadModel(
            'wallet', 
            new Wallet());
            
        $this->loadModel(
            'withdraw', 
            new Withdraw());
            
        $this->loadModel(
            'forgotpass', 
            new ForgotPassword());
            
        $this->email->setHost('iix18.idcloudhost.com', 465, 30);
    }
    
    public function sendemail() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'POST') {
            $email = $this->req->Post('email');
            $username = $this->req->Post('username');
            
            $result = $this->forgotpass->createForgotPassword(
                $email, 
                $username);
            
            if($result['result']) {
                $data = $result['data'];
                $hashid = hash('sha256', $data['id']);
                // send email
                $this->email->auth(
                    'no-reply@ethel-world.com', 
                    '!ciR.(w=r~~M');
                
                $this->email->from(
                    'no-reply@ethel-world.com', 
                    'Relasi Bisnis Bersama');
                    
                $this->email->to($email);
                
                $this->email->subject = 'Kode konfirmasi lupa password';
                $this->email->message = 'kode : <h3>'.$data['pin'].'</h3>';
                
                if($this->email->send()) {
                    $json_message = array(
                        'status'=> true, 
                        'data'=> array(
                            'hashid'=> $hashid
                        )
                    );
                }
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $result['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function resend() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'POST') {
            $email      = $this->req->Post('email');
            // $username   = $this->req->Post('username');
            
            // $result = $this->forgotpass->createForgotPassword($email, $username);
            
            $result = $this->forgotpass->createForgotPassword($email);
            
            if($result['result']) {
                $data = $result['data'];
                $hashid = hash('sha256', $data['id']);
                // send email
                
                $this->email->auth(
                    'no-reply@ethel-world.com', 
                    ',2TgpkY[8m=;');
                
                $this->email->from(
                    'no-reply@ethel-world.com', 
                    'Relasi Bisnis Bersama');
                    
                $this->email->to($email);
                
                $this->email->subject = 'Kode konfirmasi lupa password';
                $this->email->message = 'kode : <h3>'.$data['pin'].'</h3>';
                if($this->email->send()) {
                    $json_message = array(
                        'status'=> true, 
                        'data'=> array(
                            'hashid'=> $hashid
                        )
                    );
                }
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $result['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function confirmcode() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'POST') {
            $hashid = $this->req->Post('hashid');
            $email  = $this->req->Post('email');
            $code   = $this->req->Post('code');
            
            $result = $this->forgotpass->checkPin(
                $hashid, 
                $email, 
                $code);
            
            if($result['result']) {
                $data = $result['data'];
                $hashid = $data['hashid'];
                
                $json_message = array(
                    'status'=> true, 
                    'data'=> array(
                        'hashid'=> $hashid));
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $result['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function changepass() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'POST') {
            $email          = $this->req->Post('email');
            $hashid         = $this->req->Post('hashid');
            $password       = $this->req->Post('password');
            $re_password    = $this->req->Post('re_password');
            $code           = $this->req->Post('code');
            
            if($password === $re_password) {
                $result = $this->forgotpass->changePassword(
                    $password, 
                    $email, 
                    $code, 
                    $hashid);
                
                if($result['result']) {
                    $json_message = array(
                        'status'=> true, 
                        'data'=> array(
                            'msg'=> $result['msg']));
                } else {
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> $result['msg']);
                }
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'password pertama tidak sama dengan password kedua');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function getpaymentinfo() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            
            $payment = $this->user->InfoAccountPayment($id);
            
            if($payment['return']) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> $payment['data']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $payment['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function register() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        if($this->req->getMethod() === 'POST') {
            $referal    = $this->req->Post('referal');
            $wa         = $this->req->Post('wa');
            $email      = $this->req->Post('email');
            $name       = $this->req->Post('name');
            $gender     = $this->req->Post('gender');
            $occupation = $this->req->Post('occupation');
            $company    = $this->req->Post('company');
            $province   = $this->req->Post('province');
            $city       = $this->req->Post('city');
            $fb         = $this->req->Post('fb');
            $ig         = $this->req->Post('ig');
            $tiktok     = $this->req->Post('tiktok');
            $olshop     = $this->req->Post('olshop');
            $yt         = $this->req->Post('yt');
            $password   = $this->req->Post('password');
            $repassword = $this->req->Post('repassword');
            
            $lenwa = strlen(trim($wa));
            
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $json_message = array(
                    'status'=> false,
                    'msg'=> 'Email yang dimasukan tidak valid');
            }else if($name === '' || strlen($name) > 50 ) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Nama tidak boleh kosong dan tidak boleh lebih dari 50 karakter');
            }else if($referal === '') {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Harus memasukan kode referal');
            }else if($lenwa < 7 || $lenwa > 15) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Nomor telepon tidak valid.');
            }else if($password !== $repassword) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'password tidak sama');
            }else {
                $id_referal = $this->user->GetIdByReferal($referal);
                
                if($id_referal['return']) {
                    $id = $id_referal['id'];
                    
                    $result = $this->user->RegisterAccount(
                        $id, 
                        $name, 
                        $wa, 
                        $email,
                        $gender,
                        $occupation, 
                        $company, 
                        $province, 
                        $city, 
                        $fb, 
                        $ig, 
                        $tiktok, 
                        $olshop,
                        $yt, 
                        $password);
                    
                    if($result['return']) {
                        $json_message = array(
                            'status'=> true, 
                            'data'=> $result['data']);
                    } else {
                        $json_message = array(
                            'status'=> false, 
                            'msg'=> $result['msg'], 
                            'id'=> $result['id']);
                    }
                } else {
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> $id_referal['msg']);
                }
            }
        }
        
        return $this->res->json($json_message);
    }
}