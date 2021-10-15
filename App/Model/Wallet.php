<?php

namespace App\Model;
use System\Model as Model;

class Wallet extends Model{
    public function getMoney() {

    }

    public function getListLogWallet($sort, $sortby, $index, $limit) {
        
    }

    public function getListLogWalleyBetweenDate($start_date, $end_date, $sort, $sortby, $index, $limit) {
        // SELECT company_wallet_log WHERE date_created BETWEEN $start_date AND $end_date
    }
    
    public function getUserLogWallet($id, $index, $limit) {
        $query = 'SELECT 
            acc.id_member, 
            acc.wa, 
            awl.type, 
            awl.date_created 
                FROM 
                    account_wallet_log AS awl INNER JOIN account AS acc ON awl.from_id = acc.id 
                WHERE 
                    awl.to_id = ? AND awl.type = ? 
                ORDER BY 
                    date_created DESC 
                LIMIT 
                    '.$index.', '.$limit;
        
        $list = $this->db->rawQuery($query, array($id, 0));
        return $list;
    }
    
    public function getTotalWallet($id) {
        $query = 'SELECT SUM(cash) AS total_cash FROM account_wallet_log WHERE to_id = ? AND type = ?';
        $total = $this->db->rawQuery($query, array($id, 0));
        return $total[0]['total_cash'];
    }
    
    public function getTotalCashBack($id) {
        $cashback = $this->getSum('cash', 'to_id = ? AND type = ?', array($id, 0));
        if($cashback >= 0) {
            return array('return'=> true, 'money'=>$cashback);
        } else {
            return array('return'=> false, 'msg'=> 'Terjadi kesalahan');
        }
    }
    
    public function getCashBackGeneration($id) {
        $accounts = $this->db->selectColumns(array('money'), 'account', 'id = ?', array($id));
        $account = $accounts[0];
        if($account) {
            return array('return'=> true, 'money'=>$account['money']);
        } else {
            return array('return'=> false, 'msg'=> 'Terjadi kesalahan');
        }
    }
    
    public function getDetailBonusGeneration($id, $genid, $index, $limit) {
        $query = 'SELECT 
            acc.id_member AS idmember, 
            acc.wa, awl.type, 
            awl.date_created AS bonus_date, 
            awl.cash AS cash_bonus 
                FROM 
                    account_wallet_log AS awl INNER JOIN account AS acc ON awl.from_id = acc.id 
                WHERE 
                    awl.to_id = ? AND 
                    awl.type = ? AND 
                    from_id IN(SELECT 
                                id 
                                    FROM account_generation 
                                    WHERE gen_'.$genid.'= '.$id.'
                                ) 
                ORDER BY date_created DESC LIMIT '.$index.', '.$limit;
        
        $list = $this->db->rawQuery($query, array($id, 0));
        
        if(isset($list)) {
            return array('return'=> true, 'list'=> $list);
        } else {
            return array('return'=> false, 'msg'=> 'Terjadi kesalahan');
        }
    }
    
    public function getAllBonusGeneration($id) {
        $query = 'SELECT 
            SUM(CASE WHEN ag.gen_1 = ? THEN awl.cash ELSE 0 END) AS cash_gen_1,
            SUM(CASE WHEN ag.gen_2 = ? THEN awl.cash ELSE 0 END) AS cash_gen_2,
            SUM(CASE WHEN ag.gen_3 = ? THEN awl.cash ELSE 0 END) AS cash_gen_3,
            SUM(CASE WHEN ag.gen_4 = ? THEN awl.cash ELSE 0 END) AS cash_gen_4,
            SUM(CASE WHEN ag.gen_5 = ? THEN awl.cash ELSE 0 END) AS cash_gen_5 
            FROM `account_wallet_log` AS awl INNER JOIN `account_generation` AS ag ON awl.from_id = ag.id 
            WHERE awl.to_id = ?';
            
        $gensbonus = $this->db->rawQuery($query, array($id, $id, $id, $id, $id, $id));
        
        if($gensbonus) {
            $genbonus           = $gensbonus[0];
            $bonus_gen_one      = $genbonus['cash_gen_1'];
            $bonus_gen_two      = $genbonus['cash_gen_2'];
            $bonus_gen_three    = $genbonus['cash_gen_3'];
            $bonus_gen_four     = $genbonus['cash_gen_4'];
            $bonus_gen_five     = $genbonus['cash_gen_5'];
            
            return array(
                array('index'=> 1, 'total'=> $bonus_gen_one ? $bonus_gen_one : '0'), 
                array('index'=> 2, 'total'=> $bonus_gen_two ? $bonus_gen_two : '0'), 
                array('index'=> 3, 'total'=> $bonus_gen_three ? $bonus_gen_three : '0'), 
                array('index'=> 4, 'total'=> $bonus_gen_four ? $bonus_gen_four : '0'), 
                array('index'=> 5, 'total'=> $bonus_gen_five ? $bonus_gen_five : '0')
            );
        } else {
            return null;
        }
    }
    
    public function getSum($column, $where = '', $value = array()) {
        $total = $this->db->rawQuery('SELECT SUM('.$column.') AS total FROM account_wallet_log WHERE '.$where, $value);
        if(isset($total)) {
            $res = $total[0]['total'];
            return is_null($res) ? 0 : $res;
        } else {
            return -1;
        }
    }

    public function len($where = '', $value = array()){ // count rows of admin table
        return $this->db->recordCount('company_wallet_log', $where, $value);
    }
}