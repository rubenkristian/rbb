<?php

namespace App\Model;
use System\Model as Model;

class Withdraw extends Model{
    
    private function generateCode($length = 6) {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomCode = '';
        for($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomCode;
    }

    public function createWithDraw($id, $cash) {
        $accounts = $this->db->selectColumns(array('money'), 'account', ' id = ? AND money >= ? AND active = 1 AND is_deleted = 0', array($id, $cash));
        
        $account = $accounts[0];
        
        if($account) {
            if($account['money'])
            $today = date('Y-m-d h:i:s');
            $code = $this->generateCode();
            $fields = array('cash', 'id_account', 'date_created', 'code_confirm');
            $values = array($cash, $id, $today, $code);
            $rows = $this->db->insert('withdraw_log', $fields, $values);
            if($rows) {
                return array('return'=> true, 'data'=> array('id'=> $rows, 'msg'=> 'Withdraw berhasil dikonfirmasi.'), 'code'=> $code);
            } else {
                return array('return'=> false, 'msg'=> "Terjadi kesalahan, tidak dapat melakukan withdraw.");
            }
        } else {
            return array('return'=> false, 'msg'=> "Terjadi kesalahan");
        }
    }
    
    public function confirmWithDraw($id, $id_withdraw, $code) {
        $users = $this->db->selectColumns(array('password'), 'account', ' id = ?', array($id));
        if($users[0]) {
            $user = $users[0];
            $hashpass = $user['password'];
            
            if($hashpass == hash('sha256', $code)){
                $withdraw = $this->db->selectColumns(array('code_confirm', 'cash'), 'withdraw_log', ' id = ?', array($id_withdraw));
                if($withdraw[0]) {
                    $code_confirm = $withdraw[0]['code_confirm'];
                    $cash = $withdraw[0]['cash'];
                    // if($code === $code_confirm) {
                        $query = 'UPDATE account SET `money` = `money` - '.$cash.' WHERE id = ? AND `money` >= ?';
                        
                        $rows = $this->db->rawQuery($query, array($id, $cash), 2);
                        
                        if($rows) {
                            $fields = array('is_verified');
                            $values = array(1);
                            $update = $this->db->update('withdraw_log', $fields, $values, ' id ='.$id_withdraw);
                            
                            if($update) {
                                return array('return'=> true, 'msg'=> "Withdraw terkonfirmasi");
                            } else {
                                return array('return'=> false, 'msg'=> "Gagal melakukan withdraw.");
                            }
                        } else{
                            return array('return'=> false, 'msg'=> 'Terjadi kesalahan gagal melakukan withdraw');
                        }
                    // } else {
                    //     return array("return"=> false, "msg"=> "Code Salah");
                    // }
                } else {
                    return array('return'=> false, 'msg'=>"Not Found");
                }
            } else {
                return array('return'=> false, 'msg'=>'password salah');
            }
        } else {
            return array('return'=> false, 'msg'=>'Terjadi kesalahan.');
        }
    }
    
    public function getWithdrawHistory($id, $index, $limit) {
        $list = $this->db->selectColumns(array('id', 'cash', 'id_account', 'is_verified', 'confirm', 'date_created'), 'withdraw_log', 'id_account = ? AND is_verified = 1 ORDER BY date_created DESC LIMIT '.$index.', '.$limit, array($id));
        
        if($list) {
            return array('status'=> true, 'rows'=> $list);
        } else {
            return array('status'=> false, 'msg'=> 'Tidak ada data');
        }
    }

    public function verifiedWithDraw($id) {
        $today = date('Y-m-d h:i:s');
        $fields = array('confirm', 'date_verified');
        $values = array(1, $today);
        $rows = $this->db->update('withdraw_log', $fields, $values, ' id = '.$id);
        
        return $rows;
    }

    public function getWithdrawDetail($id, $status) {
        $query = 'SELECT withdraw_log.id,
                         withdraw_log.cash, 
                         withdraw_log.id_account, 
                         withdraw_log.is_verified, 
                         withdraw_log.date_created, 
                         account.fullname,
                         account.bank_account_number, 
                         account.bank_account_name, 
                         account.bank_name,
                         account.wa,
                         account.id_member,
                         bank.name
                            FROM withdraw_log INNER JOIN account ON account.id = withdraw_log.id_account INNER JOIN bank ON bank.id = account.bank_name WHERE 
                                withdraw_log.is_verified = ? AND withdraw_log.id = ?';

        $withdraw_detail = $this->db->rawQuery($query, array($status, $id));
        return $withdraw_detail;
    }

    public function getListWithdraw($search, $sort, $sortby, $index, $limit) {
        if($search === '') {
            $len_withdraw = $this->len('confirm = ? AND is_verified = ?', array(1, 1));
        } else {
            $len_withdraw = $this->len('confirm = ? AND is_verified = ?', array(1, 1));
        }

        $query = 'SELECT 
            withdraw_log.id, 
            withdraw_log.cash, 
            withdraw_log.id_account, 
            withdraw_log.is_verified, 
            withdraw_log.date_verified, 
            withdraw_log.date_created, 
            account.fullname, 
            account.bank_name, 
            account.bank_account_number, 
            account.bank_account_name, 
            account.bank_name 
            FROM withdraw_log INNER JOIN account ON account.id = withdraw_log.id_account WHERE account.fullname LIKE ? AND withdraw_log.is_verified = 1 AND withdraw_log.confirm = 1 ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.','.$limit;

        $list = $this->db->rawQuery($query, array('%'.$search.'%'));

        return array('countall'=>$len_withdraw, 'list'=>$list);
    }

    public function getListWithdrawRequest($search, $sort, $sortby, $index, $limit) {
        $len_withdraw = $this->len('is_verified = ? AND confirm = ?', array(1, 0));

        $query = 'SELECT 
            withdraw_log.id, 
            withdraw_log.cash, 
            withdraw_log.id_account, 
            withdraw_log.is_verified, 
            withdraw_log.date_created, 
            account.id_member,
            account.fullname, 
            account.bank_name, 
            account.bank_account_number, 
            account.bank_account_name, 
            account.bank_name
                FROM withdraw_log INNER JOIN account ON account.id = withdraw_log.id_account WHERE account.fullname LIKE ? AND withdraw_log.is_verified = 1 AND withdraw_log.confirm = 0 ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.','.$limit;

        $list = $this->db->rawQuery($query, array('%'.$search.'%'));

        return array('countall'=>$len_withdraw, 'list'=>$list);
    }
    
    public function getTotalWithdraw($id) {
        $withdraw = $this->getSum(
            'cash', 
            'id_account = ? AND is_verified = 1', 
            array($id));
            
        if($withdraw >= 0) {
            return array('return'=> true, 'money'=>$withdraw);
        } else {
            return array('return'=> false, 'msg'=> 'Terjadi kesalahan');
        }
    }

    public function getDetailWithdraw($id) {
        // 'SELECT * FROM withdraw_log WHERE id = ?'
    }
    
    public function getListWithdrawal($id, $index, $limit) {
        // $log_withdraw = 'SELECT '
    }
    
    public function getSum($column, $where = '', $value = array()) {
        $total = $this->db->rawQuery('SELECT SUM('.$column.') AS total FROM withdraw_log WHERE '.$where, $value);
        if(isset($total)) {
            $res = $total[0]['total'];
            return is_null($res) ? 0 : $res;
        } else {
            return -1;
        }
    }

    public function len($where = '', $value = array()){ // count rows of admin table
        return $this->db->recordCount('withdraw_log', $where, $value);
    }
}