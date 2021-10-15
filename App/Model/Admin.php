<?php

namespace App\Model;
use System\Model as Model;

class Admin extends Model{
    private function checkUsername($username){  // check if username is not used
        return $this->len("username = ?", array($username)) > 0;
    }

    public function insert($name, $username, $password){
        if(!$this->checkUsername($username)){
            $fields = array(
                'name', 
                'username', 
                'password',
                'date_created');
            $values = array(
                $name, 
                $username, 
                password_hash(
                    $password, 
                    PASSWORD_BCRYPT, 
                    ['cost'=>12]),  
                date('Y-m-d'));
            $lastid = $this->db->insert(
                'admin_tabel', 
                $fields, 
                $values);
            return array(
                'return'=>true, 
                'id'=>$lastid);
        }
        return array(
            'return'=>false, 
            'msg'=>'Username sudah digunakan');
    }

    public function updateName($id, $name){
        $fields = array('name');
        $values = array($name);
        $updated = $this->db->update(
            'admin', 
            $fields, 
            $values, 
            'id = '.$id);
        return $updated;
    }

    public function updateAccount(
        $id, 
        $username, 
        $password){
        $fields = array(
            'username', 
            'password');
        $values = array(
            $username, 
            $password);
        $updated = $this->db->update(
            'admin', 
            $fields, 
            $values, 
            'id = '.$id);
        return $updated;
    }
    
    public function updatePassword($id, $newpassword) {
        $fields = array("password");
        $values = array(
            password_hash(
                $newpassword, 
                PASSWORD_BCRYPT, 
                ['cost'=>12]));
        $updated = $this->db->update(
            'admin', 
            $fields, 
            $values, 
            'id = '.$id);
            
        if($updated) {
            $ip = $this->getUserIP();
            $this->insertLogAdmin($id, 10, $id.','.$ip);
        }
        return $updated;
    }
    
    public function checkoldpassword($id, $password) {
        $user = $this->db->select(
            'admin', 
            'id = ?', 
            array($id));
        
        if(isset($user)){
            $hashpass = $user[0]['password'];
            
            if(password_verify($password, $hashpass)){
                return 1;
            } else {
                return 0;
            }
        }
        
        return -1;
    }

    public function delete($id){
        return $this->db->delete(
            'admin', 
            'id = ?', 
            array($id));
    }

    public function selectRows(
        $search, 
        $order, 
        $sort, 
        $index, 
        $rows){
        $values = array();
        $query = 'SELECT * FROM `admin` ORDER BY '.$order.' '.$sort.' LIMIT '. $index.','.$rows;
        if(!empty($search)){
            $src = '%'.trim($search).'%';
            $query = 'SELECT * FROM `admin` WHERE `name` LIKE ? OR `username` LIKE ? ORDER BY '.$order.' '.$sort .' LIMIT '.$index.', '.$rows;
            $values = array($src, $src);
        }
        $list = $this->db->rawQuery($query, $values);
    }

    public function len($where = "", $value = array()){ // count rows of admin table
        return $this->db->recordCount("admin", $where, $value);
    }
    
    public function listTable($limit, $index, $order, $sort, $search){ // query to show rows of admin table
        if(empty($search)){
            $list = $this->db->selectColumns(
                array(
                    'name',
                    'username', 
                    'id'), 
                'admin',
                'ORDER BY '.$order.' '.$sort.' LIMIT '. $index.', '.$limit, array());
        }else{
            $search = "%".$search."%";
            $list = $this->db->selectColumns(
                array(
                    'name', 
                    'username', 
                    'id'), 
                'admin',
                ' username LIKE ? OR name LIKE ? ORDER BY '.$order.' '.$sort.' LIMIT '.$index.', '.$limit, array($search, $search));
        }
        return $list;
    }
    
    public function listHistory($limit, $index, $order, $sort, $search) {
        if(empty($search)){
            $query = 'SELECT admin.username AS name, log_admin.id_admin AS id, log_admin.log_content AS detail, log_admin.date, admin_action.action, admin_action.id AS actionid FROM log_admin INNER JOIN admin ON admin.id = log_admin.id_admin INNER JOIN admin_action ON admin_action.id = log_admin.log_type ORDER BY '.$order.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $list = $this->db->rawQuery($query, array());
        }else{
            $search = "%".$search."%";
            $query = 'SELECT admin.username AS name, log_admin.id_admin AS id, log_admin.log_content AS detail, log_admin.date, admin_action.action, admin_action.id AS actionid FROM log_admin INNER JOIN admin ON admin.id = log_admin.id_admin INNER JOIN admin_action ON admin_action.id = log_admin.log_type WHERE log_admin.date = ? ORDER BY '.$order.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $list = $this->db->rawQuery($query, array($search));
        }
        return $list;
    }
    
    private function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
          $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
          $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
    
        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }
    
        return $ip;
    }
    
    
    public function insertLogAdmin($id, $type, $content) {
        $fields = array('id_admin', 'log_type', 'log_content', 'date');
        $values = array($id, $type, $content, date('Y-m-d h:i:s'));
        $this->db->insert('log_admin', $fields, $values);
    }

    public function getNotif() {
        $expired = $this->lenTable(
        'account', 
        'verified = ? AND type_account != ? AND is_deleted = ? AND 
datecreated < NOW() - INTERVAL 2 DAY',
        array(0, 3, 0));
        $not_verified = $this->lenTable(
        'account', 
        'verified = ? AND type_account != ? AND is_deleted = ? AND 
datecreated >= NOW() - INTERVAL 2 DAY',
        array(0, 3, 0));
        $withdraw_request = $this->lenTable(
            'withdraw_log', 
            'is_verified = ? AND confirm = ?',
            array(1, 0));

        return array(
            'people'=>$not_verified, 
            'withdraw'=> $withdraw_request,
            'expired'=> $expired);
    }
    
    public function lenHistory($where = '', $value = array()) {
        return $this->db->recordCount("log_admin", $where, $value);
    }

    public function lenTable($table = "", $where = "", $value = array()){ // count rows of admin table
        return $this->db->recordCount($table, $where, $value);
    }
}