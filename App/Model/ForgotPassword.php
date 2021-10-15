<?php

namespace App\Model;
use System\Model as Model;

class ForgotPassword extends Model{
    
    private function generateCode($length = 6) {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomCode = '';
        for($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomCode;
    }
    
    public function createForgotPassword($email, $username) {
        $accounts = $this->db->selectColumns(array('id'), 'account', 'email = ? AND id_member = ?', array($email, $username));
        $account = $accounts[0];
        if($account) {
            $id = $account['id'];
            $forgotCheck = $this->db->selectColumns(array('id'), 'forgot_password', 'id = ?', array($id));
            $result = null;
            $pin = $this->generateCode();
            if($forgotCheck[0]) {
                $fields = array('pin');
                $values = array($pin);
                $result = $this->db->update('forgot_password', $fields, $values, 'id = '.$id);
            } else {
                $fields = array('id', 'pin');
                $values = array($id, $pin);
                $result = $this->db->insert('forgot_password', $fields, $values, 2);
            }
            
            if($result) {
                return array('result'=> true, 'data'=> array('id'=> $id, 'pin'=> $pin));
            } else {
                return array('result'=> false, 'msg'=> 'Terjadi kesalahaan.');
            }
        } else {
            return array('result'=> false, 'msg'=> 'Alamat email tidak terdaftar');
        }
    }
    
    public function checkPin($hashid, $email, $pin) {
        $accounts = $this->db->selectColumns(array('id'), 'account', 'email = ?', array($email));
        $account = $accounts[0];
        if($account) {
            $id = $account['id'];
            
            if($hashid === hash('sha256', $id)) {
                $forgot_pass = $this->db->selectColumns(array('datecreated'), 'forgot_password', 'id = ? AND pin = ?', array($id, $pin));
                if($forgot_pass[0]) {
                    return array('result'=> true, 'data'=> array('hashid'=> $hashid));
                } else {
                    return array('result'=> false, 'msg'=> 'kode salah');
                }
            } else {
                return array('result'=> false, 'msg'=> 'Terjadi kesalahan.');
            }
        } else {
            return array('result'=> false, 'msg'=> 'Terjadi kesalahan.');
        }
    }
    
    public function changePassword($password, $email, $code, $hashid) {
        $accounts = $this->db->selectColumns(array('id'), 'account', 'email = ?', array($email));
        $account = $accounts[0];
        if($account) {
            $id = $account['id'];
            
            if($hashid === hash('sha256', $id)) {
                $forgot_pass = $this->db->selectColumns(array('datecreated'), 'forgot_password', 'id = ? AND pin = ?', array($id, $code));
                if($forgot_pass[0]) {
                    $fields = array('`password`');
                    $values = array(hash('sha256', $password));
                    $result = $this->db->update('account', $fields, $values, ' id = '.$id);
                    
                    if($result) {
                        return array('result'=> true, 'msg'=> 'Password berhasil diganti.');
                    } else {
                        return array('result'=> false, 'msg'=> 'Gagal mengubah password.');
                    }
                } else {
                    return array('result'=> false, 'msg'=> 'Terjadi kesalahan '.$id.' code '.$code);
                }
            } else {
                return array('result'=> false, 'msg'=> 'Terjadi kesalahan.');
            }
        } else {
            return array('result'=> false, 'msg'=> 'Terjadi kesalahan.');
        }
    }
}