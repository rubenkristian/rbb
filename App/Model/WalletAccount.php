<?php

namespace App\Model;
use System\Model as Model;

class WalletAccount extends Model{

    public function getWallet($id) {
        $users = $this->db->selectColumns(array('id', 'money'));
        $user = $users[0];
        return $user;
    }

    // type 1 = receive, type 2 = withdraw
    public function getListWallet($id, $sort, $sortby, $index, $limit) {
        $len_wallet = $this->len('to_id = ?', array($id));
        $query = 'SELECT cash, (SELECT fullname FROM account WHERE id = account_wallet_log.from_id) AS cash_send, (SELECT fullname FROM account WHERE id = account_wallet_log.to_id) AS cash_receiver, from_id, to_id, `type`, date_created FROM account_wallet_log WHERE to_id = ? ORDER BY $sortby $sort LIMIT $index, $limit';
        $list = $this->db->rawQuery($query, array($id));
        return $list;
    }

    public function len($where = '', $value = array()){ // count rows of admin table
        return $this->db->recordCount('account_wallet_log', $where, $value);
    }
}