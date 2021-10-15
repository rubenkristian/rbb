<?php 
namespace App\Model;
use System\Model as Model;

use App\Library\Contact as Contact;
use App\Library\ContactList as ContactList;
use App\Library\Google\ByteBuffer as ByteBuffer;
use App\Library\Google\Constants as Constants;
use App\Library\Google\FlatbufferBuilder as FlatBufferBuilder;
use App\Library\Google\Struct as Struct;
use App\Library\Google\Table as Table;
use ZipArchive;

class User extends Model{
    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function createUser(
        $name, 
        $username,
        $password,
        $account){
        
        $fields = array(
            'name', 
            'user_type', 
            'username', 
            'password', 
            'date_created');
        
        $values = array(
            $name, 
            $account, 
            $username, 
            password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]) , 
            date('Y-m-d H:i:s'));

        $user   = $this->db->insert(
            'tbl_user', 
            $fields, 
            $values);
        
        if($user){
            return true;
        }else{
            return false;
        }
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

    public function checkAdmin(
        $username, 
        $password, 
        $action_type = 1, 
        $content = ''){
        $user = $this->db->select(
            'admin', 
            'username = ?', 
            array($username));
        
        if(isset($user)){
            $hashpass = $user[0]['password'];
            
            if(password_verify($password, $hashpass)){
                $admin_name = $user[0]['username'];
                $id = $user[0]['id'];
                $ip = $this->getUserIP();
                $content .= ','.$ip;
                
                if($action_type === 1) {
                    $content = $admin_name.','.$ip;
                }
                $this->insertLogAdmin($id, $action_type, $content);
                
                return array(
                    'status' => true, 
                    'data' =>array(
                        'username' => $admin_name, 
                        'id' => $id)
                    );
            }
            
            return array(
                'status'=>false, 
                'msg'=>'password untuk akun ini salah.');
        }else{
            return array(
                'status'=>false, 
                'msg'=>'user tidak ditemukan');
        }
    }

    public function checkUser($wa, $password){
        $query = 'SELECT 
                account.id,
                account.fullname,
                account.password,
                account.wa,
                account.type_account,
                account.tac_agreement,
                account.id_member,
                account.occupation,
                account.province,
                account.city,
                city.name AS city_name,
                province.name AS province_name
                FROM account
                    INNER JOIN province ON account.province = province.id 
                    INNER JOIN city ON account.city = city.id
                    WHERE id_member = ? AND verified = 1 AND is_deleted = 0';
                    
        $users = $this->db->rawQuery($query, array($wa));
        
        if(isset($users)){
            $user = $users[0];
            $hashpass = $user['password'];
            
            if($hashpass == hash('sha256', $password)){
                return array(
                    'status'=>true, 
                    'data' => array(
                        'id' =>$user['id'], 
                        'name' =>$user['fullname'], 
                        'wa' => $user['wa'], 
                        'type' => $user['type_account'], 
                        'tac_agreement' => $user['tac_agreement'], 
                        'id_member' => $user['id_member'], 
                        'occupation' => $user['occupation'], 
                        'address' => $user['province_name'].' '.$user['city_name']
                        )
                    );
            }else{
                return array(
                    'status' => false, 
                    'msg' => 'username atau password salah.');
            }
        }else{
            return array(
                'status' => false, 
                'msg' => 'username atau password salah.');
        }
    }
    
    public function getPhoneNumber($id) {
        $accounts = $this->db->selectColumns(
            array('wa'), 
            'account', 
            'id = ?', 
            array($id));
            
        if(isset($accounts)) {
            $account = $accounts[0];
            return array(
                'result'=> true,
                'number'=> $account['wa']);
        } else {
            return array(
                'result'=> false, 
                'msg'=> "Terjadi kesalahan");
        }
    }

    public function GetPartner($id, $index, $limit) {
        $accounts = $this->db->selectColumns(
            array(
                'id', 
                'id_member', 
                'wa', 
                'fullname', 
                'verified', 
                'datecreated'), 
            'account', 
            ' id_created = ? ORDER BY datecreated DESC LIMIT '.$index.', '.$limit, 
            array($id));
        
        if(isset($accounts)) {
            return array(
                'status'=> true, 
                'rows'=> $accounts);
        } else {
            return array(
                'status'=> false, 
                'msg'=> 'Tidak ada partner.');
        }
    }
    
    public function getPending(
        $search, 
        $id, 
        $index, 
        $limit) {
        $values = array($id);
        $where  = ' id_created = ? AND verified = 0 AND is_deleted = 0';
        
        if(trim($search) != '') {
            $src = '%'.trim($search).'%';
            $where = ' id_created = ? AND verified = 0 AND fullname LIKE ? AND is_deleted = 0';
            array_push($values, $src);
        }
        
        $accounts = $this->db->selectColumns(
            array('id', 'wa', 'fullname'), 
            'account', 
            $where.' ORDER BY datecreated DESC LIMIT '.$index.', 
            '.$limit, 
            $values);
        
        if(isset($accounts)) {
            return array(
                'status'=> true, 
                'rows'=> $accounts);
        } else {
            return array(
                'status'=> false, 
                'msg'=> 'Tidak ada data');
        }
    }
    
    public function getFiveLastPending($id) {
        $accounts = $this->db->selectColumns(
            array(
                'id', 
                'wa', 
                'fullname'), 
            'account', 
            ' id_created = ? AND verified = 0 ORDER BY datecreated DESC LIMIT 5', 
            array($id));
        
        if(isset($accounts)) {
            return array(
                'status'=> true, 
                'rows'=> $accounts);
        } else {
            return array(
                'status'=> false,
                'msg'=> 'Tidak ada data');
        }
    }

    public function ChangePassword(
        $id, 
        $old_password, 
        $new_password) {
        $users = $this->db->select(
            'account', 
            'id = ? AND verified = 1', 
            array($id));
            
        $user = $users[0];
        if($user){
            $hashpass = $user['password'];
            
            if($hashpass == hash('sha256', $old_password)){
                $fields = array('password');
                $values = array(hash('sha256', $new_password));
                $row = $this->db->update(
                    'account', 
                    $fields, 
                    $values, 
                    'id = '.$id);
                
                return array(
                    'status'=> true, 
                    'rows'=> $row);
            }else{
                return array(
                    'status'=>false, 
                    'msg'=>'password lama salah.');
            }
        } else {
            return array(
                'status'=> false, 
                'msg'=> 'Terjadi kesalahan.');
        }
    }

    public function updateSyncContact($id) {
        $fields = array('date_last_sync');
        $values = array(date('Y-m-d'));
        $rows = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id);
        return $rows;
    }
    
    public function getContactsRandom($id, $count, $lastdate) {
        $owner = $this->db->selectColumns(
            array(
                'id_member', 
                'date_last_sync', 
                'sync_count'), 
            'account', 
            ' id = ?', 
            array($id));
        
        $member_id = '';
        
        $own = $owner[0];
        
        if($own) {
            $date_now   = date('Y-m-d');
            $sync_count = $own['sync_count'];
            $member_id  = $own['id_member'];
            
            $last_sync = $own['date_last_sync'] !== '0000-00-00' ? 
            date_create($own['date_last_sync']) : date_create(date('Y-m-d', strtotime('yesterday')));
            
            $current_date   = date_create($date_now);
            $interval       = date_diff($current_date, $last_sync);
            
            $invert = $interval->invert == 0;
            $days   = $interval->d;
            $contact_count = (int)$this->lenContact('contact_for = ?', array($id));
            
            if($count > 0) {
                if($days > 0) {
                    $diff = 8800 - $contact_count;
                    if($diff <= 20) {
                        $limit = $diff;
                    } else {
                        if($contact_count >= 60) {
                            $limit = 5*$days;
                        } else {
                            $limit = 50;
                        }
                    }
                    $query = 'SELECT 
                            account.id, 
                            account.wa, 
                            account.fullname as fn, 
                            account.occupation as occ, 
                            account.company as com, 
                            city.name AS cn,
                            province.name AS pn
                            FROM account 
                                INNER JOIN province ON account.province = province.id 
                                INNER JOIN city ON account.city = city.id
                                    WHERE account.id != ? AND account.type_account != 3 AND account.id NOT IN(SELECT id_contact FROM contact WHERE contact_for = ?) AND account.verified = 1 AND account.active = 1 AND account.is_deleted = 0 ORDER BY RAND() LIMIT '.$limit;
                                    
                    $accounts = $this->db->rawQuery($query, array($id, $id));
                    
                    if($accounts) {
                        $fields     = array(
                        'id_contact', 
                        'contact_for', 
                        'dateadded');
                        
                        $values     = array();
                        $datetime   = date('Y-m-d');
                        // $name_file  = date('Ymd');
                        
                        foreach($accounts as $key => $value) {
                            array_push(
                                $values, 
                                array(
                                    $value['id'], 
                                    $id, 
                                    $datetime));
                        }
                        
                        $rows = $this->db->insert_multiple(
                            'contact', 
                            $fields, 
                            $values);
                            
                        $fields = array('date_last_sync', 'sync_count');
                        $values = array($date_now, ($sync_count + $days));
                        $rows = $this->db->update('account', $fields, $values, 'id ='.$id);
                    
                        if($rows > 0) {
                            return array(
                                'status'=> true, 
                                'contact'=> $accounts, 
                                'last_date_sync'=> $date_now);
                        }else{
                            return array(
                                'status'=> false, 
                                'msg'=> 'Terjadi Kesalahan, silahkan geser ke bawah untuk memuat ulang.');
                        }
                    } else {
                        return array(
                            'status'=> false, 
                            'msg'=> 'Tidak ada contact baru.');
                    }
                } else {
                    if($lastdate !== '') {
                        $query = 'SELECT 
                                account.id, 
                                account.wa, 
                                account.fullname as fn, 
                                account.occupation as occ, 
                                account.company as com, 
                                city.name AS cn,
                                province.name AS pn,
                                contact.dateadded
                                FROM account 
                                    INNER JOIN province ON account.province = province.id 
                                    INNER JOIN city ON account.city = city.id
                                    INNER JOIN contact ON account.id = contact.id_contact
                                         WHERE account.id != ? AND account.type_account != 3 AND contact.contact_for = ? AND account.verified = 1 AND contact.dateadded > ? GROUP BY account.id ORDER BY contact.dateadded DESC';
                                        
                        $accounts = $this->db->rawQuery($query, array($id, $id, $lastdate));
                        if($accounts) {
                            return array(
                                'status'=> true, 
                                'contact'=> $accounts, 
                                'last_date_sync'=> $accounts[0]['dateadded']);
                        } else {
                            return array(
                                'status'=> false, 
                                'msg'=> 'Sinkornasi sudah dilakukan hari ini.');
                        }
                    } else {
                        return array(
                            'status'=> false, 
                            'msg'=> 'Sinkornasi sudah dilakukan hari ini.');
                    }
                }
            } else {
                $query = 'SELECT 
                            account.id, 
                            account.wa, 
                            account.fullname as fn, 
                            account.occupation as occ, 
                            account.company as com, 
                            city.name AS cn,
                            province.name AS pn,
                            contact.dateadded
                            FROM account 
                                INNER JOIN province ON account.province = province.id 
                                INNER JOIN city ON account.city = city.id
                                INNER JOIN contact ON account.id = contact.id_contact
                                    WHERE account.id != ? AND account.type_account != 3 AND contact.contact_for = ? AND account.verified = 1 GROUP BY account.id ORDER BY contact.dateadded DESC';
                $accounts = $this->db->rawQuery($query, array($id, $id));
                
                if($accounts) {
                    return array(
                        'status'=> true, 
                        'contact'=> $accounts, 
                        'last_date_sync'=> $accounts[0]['dateadded']);
                } else {
                    $limit = 50;
                    $query = 'SELECT 
                            account.id, 
                            account.wa, 
                            account.fullname as fn, 
                            account.occupation as occ, 
                            account.company as com, 
                            city.name AS cn,
                            province.name AS pn
                            FROM account 
                                INNER JOIN province ON account.province = province.id 
                                INNER JOIN city ON account.city = city.id
                                    WHERE account.id != ? AND account.type_account != 3 AND account.id NOT IN(SELECT id_contact FROM contact WHERE contact_for = ?) AND account.verified = 1 AND account.active = 1 AND account.is_deleted = 0 ORDER BY RAND() LIMIT '.$limit;
                                    
                    $accounts = $this->db->rawQuery($query, array($id, $id));
                    
                    if($accounts) {
                        $fields     = array(
                        'id_contact', 
                        'contact_for', 
                        'dateadded');
                        
                        $values     = array();
                        $datetime   = date('Y-m-d');
                        // $name_file  = date('Ymd');
                        
                        foreach($accounts as $key => $value) {
                            array_push(
                                $values, 
                                array(
                                    $value['id'], 
                                    $id, 
                                    $datetime));
                        }
                        
                        $rows = $this->db->insert_multiple(
                            'contact', 
                            $fields, 
                            $values);
                            
                        $fields = array('date_last_sync', 'sync_count');
                        $values = array($date_now, ($sync_count + $days));
                        $rows = $this->db->update('account', $fields, $values, 'id ='.$id);
                    
                        if($rows > 0) {
                            return array(
                                'status'=> true, 
                                'contact'=> $accounts, 
                                'last_date_sync'=> $date_now);
                        }else{
                            return array(
                                'status'=> false, 
                                'msg'=> 'Terjadi Kesalahan, silahkan geser ke bawah untuk memuat ulang.');
                        }
                    }else{
                        return array(
                            'status'=> false, 
                            'msg'=> 'Terjadi Kesalahan, silahkan geser ke bawah untuk memuat ulang.');
                    }
                }
            }
        } else{
            return array(
                'status'=> false, 
                'msg'=> 'Terjadi Kesalahan, silahkan geser ke bawah untuk memuat ulang.');
        }
    }

    public function getContactRandom($id) {
        $owner = $this->db->selectColumns(
            array(
                'id_member', 
                'date_last_sync', 
                'sync_count'), 
            'account', 
            ' id = ?', 
            array($id));
        
        $member_id = '';
        
        if(isset($owner)) {
            $date_now   = date('Y-m-d');
            $own        = $owner[0];
            $sync_count = $own['sync_count'];
            $member_id  = $own['id_member'];
            
            $last_sync = $own['date_last_sync'] !== '0000-00-00' ? 
            date_create($own['date_last_sync']) : date_create(date('Y-m-d', strtotime('yesterday')));
            
            $current_date   = date_create($date_now);
            $interval       = date_diff($current_date, $last_sync);
            
            $invert = $interval->invert == 0;
            $days   = $interval->d;
            $contact_count = $this->lenContact('contact_for = ?', array($id));
            if($days > 0) {
                $limit = 5;
                
                $query = 'SELECT 
                            account.id, 
                            account.id_member, 
                            account.wa, 
                            account.fullname, 
                            account.occupation, 
                            account.company, 
                            account.province, 
                            account.city,
                            city.name AS city_name,
                            province.name AS province_name
                            FROM account 
                                INNER JOIN province ON account.province = province.id 
                                INNER JOIN city ON account.city = city.id
                                    WHERE account.id != ? AND account.type_account != 3 AND account.id NOT IN(SELECT id_contact FROM contact WHERE contact_for = ?) AND account.verified = 1 AND account.active = 1 AND account.is_deleted = 0 ORDER BY RAND() LIMIT '.$limit;
                                    
                $accounts = $this->db->rawQuery($query, array($id, $id));
                
                if($accounts) {
                    $fields     = array(
                        'id_contact', 
                        'contact_for', 
                        'dateadded');
                        
                    $values     = array();
                    $datetime   = date('Y-m-d h:i:s');
                    $name_file  = date('Ymd');
                    
                    foreach($accounts as $key => $value) {
                        array_push(
                            $values, 
                            array(
                                $value['id'], 
                                $id, 
                                $datetime));
                    }
                    
                    $rows = $this->db->insert_multiple(
                        'contact', 
                        $fields, 
                        $values);
                        
                    $fields = array('date_last_sync', 'sync_count');
                    $values = array($date_now, ($sync_count + $days));
                    $rows = $this->db->update('account', $fields, $values, 'id ='.$id);
                    
                    if($rows > 0) {
                        return array(
                            'status'=> true, 
                            'contact'=> $accounts, 
                            'last_date_sync'=> $date_now);
                    }else{
                        return array(
                            'status'=> false, 
                            'msg'=> 'Terjadi Kesalahan.');
                    }
                }else{
                    return array(
                        'status'=> false, 
                        'msg'=> 'Tidak ada contact baru.');
                }
            } else {
                return array(
                    'status'=> false, 
                    'msg'=> 'Sinkornasi sudah dilakukan hari ini.');
            }
        }else{
            return array(
                'status'=> false, 
                'msg'=> 'Terjadi Kesalahan.');
        }
    }
    
    public function getMoney($id) {
        $users = $this->db->selectColumns(
            array('money'), 
            'account', 
            'id = ?',
            array($id));
        
        if(isset($users)) {
            $user = $users[0];
            return array(
                'return'=> true, 
                'money'=> $user['money']);
        } else {
            return array(
                'return'=> true, 
                'msg'=> 'Terjadi kesalahan.');
        }
    }
    
    public function getInfoWithdraw($id) {
        $query = 'SELECT 
        acc.id_member, 
        acc.bank_account_number, 
        acc.bank_account_name, 
        acc.money, 
        bk.name, 
        bk.minimal 
            FROM account AS acc INNER JOIN bank AS bk ON acc.bank_name = bk.id 
            WHERE acc.id = ?';
        
        $account = $this->db->rawQuery($query, array($id));
        if(isset($account)) {
            $user = $account[0];
            
            return array(
                'status'=> true,
                'account'=> array(
                    'username'=> $user['id_member'],
                    'bonus'=> $user['money'],
                    'bank'=> $user['name'],
                    'minimum'=> $user['minimal'],
                    'bankAccountNumber'=> $user['bank_account_number'],
                    'bankAccountName'=> $user['bank_account_name'],
                    'withdrawalInfo' => 'Minimal withdrawl Rp 10.000,00 untuk BCA, Mandiri, BNI, BRI & Minimal withdrawl Rp 50.000,00 untuk Bank lain.'
                    )
                );
                
        } else {
            return array('status'=> false, 'msg'=> 'account not found');
        }
    }
    
    public function getPersonalAccount($id) {
        $query = 'SELECT 
            account.id,
            account.fullname,
            account.occupation,
            city.name AS cityname,
            province.name AS provincename,
            account.last_watch_ads
        FROM account INNER JOIN city ON account.city = city.id INNER JOIN province ON account.province = province.id
        WHERE
            account.id = ? AND account.is_deleted = 0';
        $accounts = $this->db->rawQuery($query, array($id));
        if($accounts) {
            $user = $accounts[0];
            
            return array(
                'status' => true,
                'account' => array(
                    'id' => $user['id'],
                    'fn' => $user['fullname'],
                    'occ' => $user['occupation'],
                    'add' => $user['provincename'].' '.$user['cityname'],
                    'datetimeads' => $user['last_watch_ads']
                    )
                );
        } else {
            return array(
                'status' => false,
                'msg' => 'Account not found');
        }
    }
    
    public function getAccount($id) {
        $account = $this->db->rawQuery('SELECT 
        account.id,
        account.fullname, 
        account.occupation, 
        account.company, 
        account.city, 
        account.province, 
        account.bank_account_name, 
        account.bank_account_number, 
        account.bank_name,
        account.money, 
        account.fb, 
        account.ig, 
        account.olshop, 
        account.tiktok, 
        account.yt, 
        account.wa,
        account.email,
        account.id_member AS username, 
        city.name AS cityname, 
        province.name AS provincename, 
        bank.name AS bankname 
        FROM 
            account INNER JOIN city ON account.city = city.id 
                    INNER JOIN province ON account.province = province.id 
                    INNER JOIN bank ON bank.id = account.bank_name 
        WHERE 
            account.id = ? AND account.is_deleted = 0 LIMIT 1', array($id));
            
        if($account) {
            $user = $account[0];
            
            return array(
                'status'=> true, 
                'account'=> array(
                    'id'=>                  $user['id'],
                    'wa'=>                  $user['wa'],
                    'email'=>               $user['email'],
                    'username'=>            strtoupper($user['username']),
                    'fullname'=>            $user['fullname'], 
                    'occupation'=>          $user['occupation'], 
                    'company'=>             $user['company'], 
                    'city'=>                $user['cityname'],
                    'id_city'=>             intval($user['city']),
                    'province'=>            $user['provincename'],
                    'id_province'=>         intval($user['province']),
                    'bank'=>                $user['bankname'], 
                    'id_bank'=>             intval($user['bank_name']),
                    'bank_account_name'=>   $user['bank_account_name'], 
                    'bank_account_number'=> $user['bank_account_number'],
                    'wallet'=>              $user['money'],
                    'fb'=>                  $user['fb'], 
                    'ig'=>                  $user['ig'], 
                    'olshop'=>              $user['olshop'], 
                    'tiktok'=>              $user['tiktok'], 
                    'yt'=>                  $user['yt']
                )
            );
        }else {
            return array('status'=> false, 'msg'=> 'account not found');
        }
    }

    public function agreeTAC($id) {
        $fields = array('tac_agreement');
        $values = array(1);
        $row = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id);
        return $row;
    }

    public function generateRandomPassword() {
        return generateRandomString(6);
    }
    
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
    
    private function generatePaymentCode($length = 3) {
        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomCode = '';
        
        for($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomCode;
    }
    
    private function createPaymentCode($id, $code) {
        $fields = array('id', 'code');
        $values = array($id, $code);
        $lastid = $this->db->insert('payment_code', $fields, $values);
        return $lastid;
    }
    
    public function GetIdByReferal($referal) {
        $users = $this->db->selectColumns(
            array('id'),
            'account', 
            'id NOT IN(1, 3, 5, 7) AND id_member = ? AND verified = 1 AND is_deleted = 0', 
            array($referal));
        
        if($users[0]) {
            $id = $users[0]['id'];
            return array(
                'return'=> true, 
                'id'=> $id);
        } else {
            return array(
                'return'=> false, 
                'msg'=> 'Kode referal tidak berlaku.');
        }
    }
    
    public function GetReferalById($id) {
        $users = $this->db->selectColumns(
            array('id_member'), 
            'account', 
            'id NOT IN(1, 3, 5, 7) AND id = ? AND verified = 1 AND is_deleted = 0',
            array($id));
        
        if(isset($users)) {
            $referal = $users[0]['id_member'];
            return array(
                'return'=> true, 
                'referal'=> $referal);
        } else {
            return array(
                'return'=> false, 
                'msg'=> 'Kode referal tidak berlaku.');
        }
    }
    
    public function RegisterAccount(
        $idcreated, 
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
        $password) {
        $checkData = $this->CheckWANumber($wa, $email);
        
        if(!$checkData){
            $date = date('Y-m-d h:i:s');
            $data_referal = $this->GetReferalById($idcreated);
            
            if(!$data_referal['return']) {
                return array(
                    'return'=>false, 
                    'msg'=>$data_referal['msg']);
            }
            
            $referal = $data_referal['referal'];
            $gens = array();
            
            $fields = array(
                'wa', 
                'email', 
                'fullname', 
                'gender', 
                'occupation', 
                'company', 
                'province', 
                'city', 
                'fb', 
                'ig', 
                'olshop', 
                'tiktok', 
                'yt', 
                'password', 
                'id_created', 
                'datecreated');
            
            $values = array(
                $wa, 
                $email,
                $name, 
                $gender,
                $occupation, 
                $company,
                $province,
                $city, 
                $fb, 
                $ig, 
                $olshop, 
                $tiktok, 
                $yt, 
                hash('sha256', $password),
                $idcreated, 
                $date);
            
            $lastid = $this->db->insert(
                'account', 
                $fields,
                $values);
            
            $gen_ids = $this->db->selectColumns(
                array(
                    'id',
                    'gen_1', 
                    'gen_2', 
                    'gen_3', 
                    'gen_4'), 
                'account_generation', 
                ' id = ?', 
                array($idcreated));
            
            $gen = $gen_ids[0];
            array_push($gens, $lastid);
            array_push($gens, $gen['id']);
            
            for($i = 0; $i < 4; $i++) {
                $id_gen = $gen['gen_'.($i + 1)];
                if($id_gen != 0) {
                    array_push($gens, $id_gen);
                }
            }
            
            $num_length = strlen((string)$lastid);
            $id_member = 'RBB';
            
            switch ($num_length) {
                case 1:
                    $id_member = $id_member.'000'.$lastid;
                    break;
                case 2:
                    $id_member = $id_member.'00'.$lastid;
                    break;
                case 3:
                    $id_member = $id_member.'0'.$lastid;
                    break;
                default:
                    $id_member = $id_member.$lastid;
                    break;
            }
            
            $fields = array(
                'id', 
                'gen_1', 
                'gen_2',
                'gen_3',
                'gen_4', 
                'gen_5');
                
            $values = $gens;
            $row    = $this->db->insert(
                'account_generation', 
                $fields, 
                $values);
            
            $codepayment = substr($id_member, -3);
            $this->createPaymentCode($lastid, $codepayment);
            
            return array(
                'return'=>true, 
                'data'=> array(
                    'id'=>$lastid, 
                    'price'=> 88000, 
                    'name'=> $name, 
                    'referal'=> $id_member, 
                    'wa'=> $wa, 
                    'date'=> $date, 
                    'code'=> $codepayment, 
                    'total'=> (88000+(int)$codepayment)
                    )
                );
        }
        
        $msg = '';
        
        if($checkData['wa'] === $wa) {
            $msg = 'No Whatsapp sudah terdaftar.';
        }
        
        return array(
            'return'=>false, 
            'msg'=>$msg,
            'id'=> $checkData['id']);
    }
    
    public function InfoAccountPayment($id) {
        $query = 'SELECT 
        account.fullname,
        account.wa,
        account.datecreated,
        payment_code.code 
            FROM 
                account INNER JOIN payment_code ON account.id = payment_code.id 
            WHERE account.id = ? AND account.verified = 0';
        
        $accounts = $this->db->rawQuery($query, array($id));
        $account = $accounts[0];
        
        if($account) {
            $codepayment = $account['code'];
            
            $num_length = strlen((string)$id);
            $id_member = 'RBB';
            
            switch ($num_length) {
                case 1:
                    $id_member = $id_member.'000'.$id;
                    break;
                case 2:
                    $id_member = $id_member.'00'.$id;
                    break;
                case 3:
                    $id_member = $id_member.'0'.$id;
                    break;
                default:
                    $id_member = $id_member.$id;
                    break;
            }
            return array(
                'return'=> true, 
                'data'=> array(
                    'id'=> $id,
                    'price'=> 88000,
                    'name'=> $account['fullname'],
                    'referal'=> $id_member, 
                    'wa'=> $account['wa'], 
                    'date'=> $account['datecreated'], 
                    'code'=> $codepayment, 
                    'total'=> (88000+(int)$codepayment)
                    ));
        } else {
            return array(
                'return'=> false,
                'msg'=> 'Data tidak ditemukan.');
        }
    }

    public function InsertContact(
        $idcreated, 
        $name, 
        $wa, 
        $email, 
        $gender, 
        $occupation, 
        $company, 
        $province, 
        $city, 
        $bank, 
        $bank_number, 
        $bank_name, 
        $fb, 
        $ig, 
        $tiktok, 
        $olshop, 
        $yt,
        $password){
        
        $checkData = $this->CheckDataUserWABANK($wa, $bank_number, $bank_name);
        
        if(!$checkData){
            $date = date('Y-m-d h:i:s');
            $data_referal = $this->GetReferalById($idcreated);
            
            if(!$data_referal['return']) {
                return array(
                    'return'=>false, 
                    'msg'=>$data_referal['msg']);
            }
            
            $referal = $data_referal['referal'];
            $gens = array();
            
            $fields = array(
                'wa', 
                'email', 
                'fullname', 
                'gender', 
                'occupation', 
                'company', 
                'province', 
                'city', 
                'bank_account_name', 
                'bank_account_number', 
                'bank_name', 
                'fb', 
                'ig', 
                'olshop', 
                'tiktok', 
                'yt', 
                'password',
                'id_created', 
                'datecreated');
            
            $values = array(
                $wa, 
                $email, 
                $name,
                $gender,
                $occupation,
                $company,
                $province,
                $city,
                $bank_name, 
                $bank_number, 
                $bank, 
                $fb, 
                $ig, 
                $olshop, 
                $tiktok,
                $yt, 
                hash('sha256', $password),
                $idcreated, 
                $date);
            
            $lastid = $this->db->insert('account', $fields, $values);
            
            $gen_ids = $this->db->selectColumns(array(
                'id', 
                'gen_1', 
                'gen_2', 
                'gen_3', 
                'gen_4'), 
                'account_generation', 
                ' id = ?', 
                array($idcreated));
            
            $gen = $gen_ids[0];
            array_push($gens, $lastid);
            array_push($gens, $gen['id']);
            
            for($i = 0; $i < 4; $i++) {
                $id_gen = $gen['gen_'.($i + 1)];
                
                if($id_gen != 0) {
                    array_push($gens, $id_gen);
                }
            }
            
            $num_length = strlen((string)$lastid);
            $id_member = 'RBB';
            
            switch ($num_length) {
                case 1:
                    $id_member = $id_member.'000'.$lastid;
                    break;
                case 2:
                    $id_member = $id_member.'00'.$lastid;
                    break;
                case 3:
                    $id_member = $id_member.'0'.$lastid;
                    break;
                default:
                    $id_member = $id_member.$lastid;
                    break;
            }
            
            $fields = array(
                'id', 
                'gen_1', 
                'gen_2', 
                'gen_3', 
                'gen_4', 
                'gen_5');
                
            $values = $gens;
            $row = $this->db->insert(
                'account_generation',
                $fields, 
                $values);
            
            $codepayment = substr($id_member, -3);
            $this->createPaymentCode($lastid, $codepayment);
            
            return array(
                'return'=>true, 
                'data'=> array(
                    'id'=>$lastid, 
                    'price'=> 88000, 
                    'name'=> $name, 
                    'referal'=> $id_member, 
                    'wa'=> $wa, 
                    'date'=> $date, 
                    'code'=> $codepayment, 
                    'total'=> (88000+(int)$codepayment)
                    )
                );
        }
        
        $msg = '';
        
        if($checkData['wa'] === $wa) 
        {
            $msg = 'No Whatsapp sudah terdaftar.';
        } else if($checkData['bank_account_number'] === $bank_number && $checkData['bank_name'] === $bank_name) {
            $msg = 'Nomor Rekening sudah terdaftar.';
        }
        
        return array('return'=>false, 'msg'=> $msg, 'id'=> $checkData['id']);
    }

    public function InsertCompanyAccount(
        $idcreated, 
        $name, 
        $wa,
        $email,
        $gender, 
        $occupation, 
        $company, 
        $province, 
        $city, 
        $bank, 
        $bank_number,
        $bank_name, 
        $fb, 
        $ig,
        $tiktok, 
        $olshop, 
        $yt) {
        
        if(!$this->CheckWANumber($wa, $email)) {
            $fields = array(
                'wa', 
                'fullname', 
                'gender', 
                'occupation', 
                'company', 
                'province', 
                'city',
                'bank_account_name',
                'bank_account_number',
                'bank_name',
                'fb', 
                'ig', 
                'olshop',
                'tiktok', 
                'yt', 
                'id_created', 
                'datecreated', 
                'type_account');
            
            $dateTime = date('Y-m-d h:i:s');
            
            $values = array(
                $wa, 
                $name, 
                $gender, 
                $occupation, 
                $company, 
                $province, 
                $city, 
                $bank_name, 
                $bank_number, 
                $bank, 
                $fb, 
                $ig, 
                $olshop,
                $tiktok, 
                $yt, 
                $idcreated,
                $dateTime, 
                3);
            
            $lastid = $this->db->insert(
                'account', 
                $fields, 
                $values);
            
            return array(
                'return'=> true, 
                'data'=> $lastid);
        }
        
        return array(
            'return'=> false, 
            'msg'=> 'No Whatsapp sudah terdaftar');
    }

    public function CheckWANumber($wa, $norek) {
        $account = $this->db->selectColumns(
            array(
                'id', 
                'id_member',
                'wa', 
                'email'), 
            'account', 
            ' wa = ? AND is_deleted = 0', 
            array($wa));
        return $account[0];
    }
    
    public function CheckDataUserWABANK($wa, $norek, $bank) {
        $account = $this->db->selectColumns(
            array(
                'id', 
                'id_member',
                'wa', 
                'email',
                'bank_account_number',
                'bank_name'), 
            'account', 
            ' (wa = ? OR (bank_account_number = ? AND bank_name = ?)) AND is_deleted = 0', 
            array($wa, $norek, $bank));
        return $account[0];
    }
    
    public function CheckDataUser($wa, $email, $banknumber) {
        $account = $this->db->selectColumns(
            array(
                'id',
                'id_member',
                'wa',
                'email',
                'bank_account_number'),
            'account',
            ' (wa = ? OR bank_account_number = ?) AND is_deleted = 0',
            array($wa, $banknumber));
        
        return $account[0];
    }

    public function CreateTempPassword($id, $password){
        $getdatenextmonth = date(
            'Y-m-d', 
            strtotime('+1 month'));
        
        $fields = array(
            'password', 
            'expired_account', 
            'verified');
        $values = array(
            hash('sha256', $password), 
            $getdatenextmonth, 
            1);
        $lastid = $this->db->update(
            'account', 
            $fields,
            $values, 
            ' id = '.$id);
        
        return $lastid > 0;
    }

    public function createPassword($id) {
        $pass_raw = $this->generateRandomString();
        $hash = hash('sha256', $pass_raw);

        $fields = array('password', 'verified');
        $values = array($hash, 1);
        $lastid = $this->db->update(
            'account', 
            $fields, 
            $values, 
            ' id = '.$id);
        
        return array('return'=>$lastid > 0, 'password'=> $pass_raw);
    }

    public function verified($id) {
        $gens = array();
        $nextid = $id;
        $today = date('Y-m-d');
        
        $fields = array('verified', 'id_member', 'date_verified');
        $num_length = strlen((string)$id);
        $id_member = 'rbb';
        
        switch ($num_length) {
            case 1:
                $id_member = $id_member.'000'.$id;
                break;
            case 2:
                $id_member = $id_member.'00'.$id;
                break;
            case 3:
                $id_member = $id_member.'0'.$id;
                break;
            default:
                $id_member = $id_member.$id;
                break;
        }
        
        $values = array(1, $id_member, $today);
        $row = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id.' AND verified = 0 AND datecreated >= NOW() - INTERVAL 2 DAY ');
        
        if($row <= 0) {
            return array(
                'return'=>false, 
                'msg'=> 'Akun gagal diverifikasi.');
        }
        
        $settings = $this->db->selectColumns(
            array(
                'cash_company', 
                'cash_account', 
                'cash_total'), 
            'setting', 
            '', 
            array());
        
        $setting        = $settings[0];
        $cash_company   = $setting['cash_company'];
        $cash_account   = $setting['cash_account'];
        $cash_total     = $setting['cash_total'];

        $today = date('Y-m-d');

        $id_check = $id;
        $gen_ids = $this->db->selectColumns(
            array(
                'id', 
                'gen_1', 
                'gen_2', 
                'gen_3', 
                'gen_4', 
                'gen_5'), 
            'account_generation', 
            ' id = ?', 
            array($id));
            
        $gen = $gen_ids[0];
        
        for($i = 0; $i < 5; $i++) {
            $id_gen = $gen['gen_'.($i + 1)];
            
            if($id_gen != 0) {
                array_push($gens, $id_gen);
            }
        }

        $query_log_generation = 'INSERT INTO account_wallet_log(
        `cash`, 
        `from_id`, 
        `to_id`, 
        `type`, 
        `date_created`)
        VALUES (?,?,?,?,?), (?,?,?,?,?), (?,?,?,?,?), (?,?,?,?,?), (?,?,?,?,?)';
                                    
        $values_log_generation = array();
        $gens_len = count($gens);
        
        foreach($gens as $i => $v) {
            $values = array(
                $cash_account, 
                $id, 
                $v, 
                0, 
                $today);
            $values_log_generation = array_merge(
                $values_log_generation,
                $values);
        }
        
        $row = $this->db->rawQuery(
            $query_log_generation, 
            $values_log_generation);

        $query = 'UPDATE account 
        SET `money` = `money` + '.$cash_account.' 
        WHERE id IN('.implode(',', $gens).')';
        
        $rows = $this->db->rawQuery($query, array());
        
        if($row <= 0) {
            return false;
        }

        // insert log account company
        $fields = array(
            'cash', 
            'id_account',
            'type', 
            'date_created');
            
        $values = array(
            $cash_company, 
            $id,
            0, 
            $today);
            
        $row    = $this->db->insert(
                    'company_wallet_log', 
                    $fields, 
                    $values);

        $fields = array('cash', 'date_update');
        $values = array($cash_total, $today);
        $row = $this->db->insert(
            'company_wallet', 
            $fields, 
            $values);

        return array(
            'return'=>true, 
            'referal'=> $id_member);
    }

    public function accountVerified($id) {
        $row = $this->db->selectColumns(
            array('verified'), 
            'account', 
            'id = ?', 
            array($id));
        if(count($row) > 0) {
            return $row[0]['verified'] === 1;
        } else {
            return false;
        }
    }
    
    public function updateAccount($id) {
        $fields = array('datecreated');
        $values = array(date('Y-m-d H:i:s'));
        $row = $this->db->update(
            'account', 
            $fields, 
            $values, 
            ' id = '.$id);
        
        return $row;
    }
    
    public function deleteAccount($id) {
        $fields = array('active','is_deleted');
        $values = array(0, 1);
        $row = $this->db->update(
            'account', 
            $fields, 
            $values, 
            ' id = '.$id);
        
        return $row;
    }

    public function getListVerifiedAccount(
        $search, 
        $sort, 
        $sortby, 
        $index, 
        $limit) {
        $len_account    = $this->len(
            'verified = ? AND type_account != ? AND active = ?', 
            array(1, 3, 1));
        $user_list      = array();
        
        if(trim($search) != '') {
            $src    = '%'.trim($search).'%';
            $value  = array($src);
            $len_account = $this->len('(fullname LIKE ? OR wa LIKE ? OR id_member LIKE ? OR id_created LIKE ?) AND (verified = ? AND type_account != ?)', array($src, $src, $src, $src, 1, 3));
            
            $query = 'SELECT 
            account.id, 
            account.id_member,
            account.wa,
            account.id_created,
            account.fullname
                FROM account 
                WHERE 
                    ('.$sortby.' LIKE ?) AND 
                    (verified = 1 AND type_account != 3 AND active = 1)
                ORDER BY '.$sortby.' '. $sort.' LIMIT '.$index.', '.$limit;
            
            $user_list = $this->db->rawQuery($query, $value);
        } else {
            $query = 'SELECT 
            account.id, 
            account.id_member, 
            account.wa, 
            account.id_created,
            account.fullname
                FROM account 
                WHERE (verified = ? AND type_account != ? AND active = 1)
            ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $user_list = $this->db->rawQuery($query, array(1,3));
        }
        
        return array('countall'=>$len_account, 'list'=>$user_list);
    }
    
    public function getListExpiredNotVerifiedAccount($search, $sort, $sortby, $index, $limit) {
        $len_account    = $this->len('verified = ? AND type_account != ? AND active = ? AND is_deleted = 0 AND datecreated < NOW() - INTERVAL 2 DAY ', array(0, 3, 1));
        $user_list      = array();
        
        if(trim($search) !== '') {
            $src    = '%'.trim($search).'%';
            $value  = array($src);
            
            $len_account = $this->lenAccountNotVerifiedExpired($src);
            
            $query = 'SELECT
            account.id,
            account.wa,
            account.id_created,
            account.fullname,
            payment_code.code
                FROM
                    account INNER JOIN payment_code ON account.id = payment_code.id
                WHERE
                    ('.$sortby.' LIKE ?) AND
                    account.verified = 0 AND
                        account.type_account != 3 AND
                        account.active = 1 AND
                        account.is_deleted = 0 AND
                    datecreated < NOW() - INTERVAL 2 DAY
                ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
            $user_list = $this->db->rawQueryType('select', $query, $value);
        } else {
            $query = 'SELECT 
            account.id, 
            account.wa, 
            account.id_created,
            account.fullname, 
            payment_code.code 
                FROM 
                    account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE 
                    (account.verified = 0 AND 
                    account.type_account != 3 AND 
                    account.active = 1 AND
                    account.is_deleted = 0) AND
                 datecreated < NOW() - INTERVAL 2 DAY 
            ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $user_list = $this->db->rawQueryType('select', $query, array());
        }
        
        return array('countall'=>$len_account, 'list'=>$user_list);
    }

    public function getListNotVerifiedAccount($search, $sort, $sortby, $index, $limit) {
        $len_account    = $this->len('verified = ? AND type_account != ? AND active = ? AND account.is_deleted = 0 AND datecreated >= NOW() - INTERVAL 2 DAY ', array(0, 3, 1));
        $user_list      = array();
        
        if(trim($search) !== '') {
            $src    = '%'.trim($search).'%';
            $value  = array($src);
            
            $len_account = $this->lenAccountNotVerified($src);
            
            $query = 'SELECT 
            account.id, 
            account.wa, 
            account.id_created,
            account.fullname, 
            payment_code.code 
                FROM 
                    account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE 
                    ('.$sortby.' LIKE ?) AND 
                    account.verified = 0 AND 
                        account.type_account != 3 AND 
                        account.active = 1 AND
                        account.is_deleted = 0 AND
                     datecreated >= NOW() - INTERVAL 2 DAY 
                ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $user_list = $this->db->rawQueryType('select', $query, $value);
        } else {
            $query = 'SELECT 
            account.id, 
            account.wa, 
            account.id_created,
            account.fullname, 
            payment_code.code 
                FROM 
                    account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE 
                    (account.verified = 0 AND 
                    account.type_account != 3 AND 
                    account.active = 1 AND
                    account.is_deleted = 0) AND
                 datecreated >= NOW() - INTERVAL 2 DAY 
            ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $user_list = $this->db->rawQueryType('select', $query, array());
        }
        
        return array('countall'=>$len_account, 'list'=>$user_list);
    }

    public function getListNonActiveAccount($search, $sort, $sortby, $index, $limit) {
        $len_account    = $this->len('verified = ? AND type_account != ? AND active = ? AND account.is_deleted = 0', array(0, 3, 0));
        $user_list      = array();
        
        if(trim($search) != '') {
            $src    = '%'.trim($search).'%';
            $value  = array($src);
            
            $len_account = $this->len('('.$sortby.' LIKE ?) AND (verified = ? AND type_account != ? AND account.is_deleted = 0)', array($src, $src, 0, 3));
            
            $query = 'SELECT 
            account.id, 
            account.wa, 
            account.fullname, 
            payment_code.code 
                FROM account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE ('.$sortby.' LIKE ?) AND 
                (account.verified = 1 AND account.type_account != 3 AND account.active = 0 AND account.is_deleted = 0) 
                ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
        
            $user_list = $this->db->rawQueryType('select', $query, $value);
        } else {
            $query = 'SELECT 
            account.id, 
            account.wa, 
            account.fullname, 
            payment_code.code 
                FROM 
                    account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE 
                    (account.verified = 1 AND 
                    account.type_account != 3 AND 
                    account.active = 0  AND 
                    account.is_deleted = 0) 
            ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit;
            
            $user_list = $this->db->rawQueryType('select', $query, array());
        }
        
        return array('countall'=>$len_account, 'list'=>$user_list);
    }

    public function getListCompanyAccount(
        $search, 
        $sort, 
        $sortby, 
        $index, 
        $limit) {
        $len_account    = $this->len(
            'verified = ? AND type_account = ?',
            array(0, 3));
            
        $user_list      = array();
        
        if(trim($search) != '') {
            $src    = '%'.trim($search).'%';
            $value  = array($src, $src);
            
            $len_account = $this->len(
                '(fullname LIKE ? OR wa LIKE ?) AND verified = ? AND type_account = ?', 
                array($src, $src, 1, 3));
            
            $user_list = $this->db->selectColumns(
                array(
                    'id', 
                    'wa', 
                    'fullname', 
                    'gender'), 
                'account', 
                '(fullname LIKE ? OR wa LIKE ?) AND (verified = 1 AND type_account = 3) ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit, 
                $value);
        } else {
            $user_list = $this->db->selectColumns(
                array(
                    'id', 
                    'wa', 
                    'fullname', 
                    'gender'), 
                'account', 
                'verified = ? AND type_account = ? ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit, 
                array(1, 3));
        }
        
        return array(
            'countall'=> $len_account, 
            'list'=> $user_list);
    }

    public function updateMedia(
        $id, 
        $fb, 
        $ig, 
        $olshop, 
        $tiktok, 
        $yt) {
        $fields = array(
            'fb', 
            'ig', 
            'olshop', 
            'tiktok', 
            'yt');
        $values = array(
            $fb,
            $ig, 
            $olshop, 
            $tiktok, 
            $yt);
        $rows = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id);
        return $rows > 0;
    }
    
    public function generationList($id) {
        $query = 'SELECT 
            COUNT(CASE WHEN gen_1 = ? THEN 1 END) AS len_gen_1, 
            COUNT(CASE WHEN gen_2 = ? THEN 1 END) AS len_gen_2, 
            COUNT(CASE WHEN gen_3 = ? THEN 1 END) AS len_gen_3, 
            COUNT(CASE WHEN gen_4 = ? THEN 1 END) AS len_gen_4, 
            COUNT(CASE WHEN gen_5 = ? THEN 1 END) AS len_gen_5 
            FROM 
                `account_generation` AS ag
            INNER JOIN
                `account` AS acc
            ON
                ag.id = acc.id
            WHERE 
                (acc.verified = 1 AND acc.is_deleted = 0) AND (ag.gen_1 = ? OR ag.gen_2 = ? OR ag.gen_3 = ? OR ag.gen_4 = ? OR ag.gen_5 = ?)';
        
        $generations = $this->db->rawQuery(
            $query, 
            array(
                $id, 
                $id, 
                $id, 
                $id, 
                $id,
                $id, 
                $id, 
                $id, 
                $id, 
                $id)
            );
        
        if($generations) {
            $generation = $generations[0];
            $gen_one    = $generation['len_gen_1'];
            $gen_two    = $generation['len_gen_2'];
            $gen_three  = $generation['len_gen_3'];
            $gen_four   = $generation['len_gen_4'];
            $gen_five   = $generation['len_gen_5'];
            
            return array(
                array(
                    'index'=> 1, 
                    'users'=> $gen_one), 
                array(
                    'index'=> 2, 
                    'users'=> $gen_two), 
                array(
                    'index'=> 3, 
                    'users'=> $gen_three), 
                array(
                    'index'=> 4, 
                    'users'=> $gen_four), 
                array(
                    'index'=> 5, 
                    'users'=> $gen_five));
        } else {
            return null;
        }
    }
    
    public function accountListGeneration(
        $id, 
        $gen, 
        $search, 
        $index, 
        $limit) {
        $query = 'SELECT 
            acc.id, 
            acc.id_member, 
            acc.wa, 
            acc.fullname, 
            acc.verified, 
            acc.datecreated 
            FROM 
                account_generation AS accgen INNER JOIN account AS acc ON accgen.id = acc.id 
            WHERE acc.is_deleted = 0 AND acc.verified = 1 AND ';
        
        $values = array();
        if(trim($search) != '') {
            $src = '%'.trim($search).'%';
            $query = $query.' acc.fullname LIKE ? AND ';
            array_push($values, $src);
        }
        array_push($values, $id);
        
        if($gen == 1) {
            $query = $query.' gen_1 = ? ';
        } else if($gen == 2) {
            $query = $query.' gen_2 = ? ';
        } else if($gen == 3) {
            $query = $query.' gen_3 = ? ';
        } else if($gen == 4) {
            $query = $query.' gen_4 = ? ';
        } else{
            $query = $query.' gen_5 = ? ';
        }
        
        $query = $query.' ORDER BY acc.id DESC LIMIT '.$index.', '.$limit;
        
        $list = $this->db->rawQuery($query, $values);
        return $list;
    }
    
    public function updateBankInfo(
        $id, 
        $bank, 
        $name, 
        $number) {
        // check bank number is not same
        $fields = array(
            'bank_account_number', 
            'bank_account_name', 
            'bank_name');
        $values = array(
            $number, 
            $name, 
            $bank);
        $bank_check = $this->checkBankActive($number, $bank);
        if(!$bank_check) {
            $res = $this->db->update(
                'account', 
                $fields, 
                $values, 
                'id = '.$id);
            
            if($res) {
                return array(
                    'status'=> true, 
                    'data'=> array(
                        'name'=> $name, 
                        'number'=> $number, 
                        'bank'=> (int)$bank)
                    );
            } else {
                return array(
                    'status'=> false, 
                    'msg'=> 'Gagal memperbaharui');
            }
        } else {
            return array(
                'status'=> false, 
                'msg'=> 'Nomor rekening sudah terdaftar.');
        }
    }
    
    public function checkBank($id, $banknumber) {
        $accounts = $this->db->selectColumns(array('id', 'bank_account_number'), 'account', 'id = ? AND bank_account_number = ?', array($id, $banknumber));
        
        return $accounts;
    }
    
    public function checkBankActive($banknumber, $bank) {
        $accounts = $this->db->selectColumns(array('bank_name', 'bank_account_number'), 'account', '(bank_account_number = ? AND bank_name = ?) AND is_deleted = 0', array($banknumber, $bank));
        
        return $accounts[0];
    }
    
    public function updateInfo(
        $id, 
        $fb, 
        $ig, 
        $olshop, 
        $tiktok, 
        $yt) {
            
        $fields = array(
            'fb', 
            'ig', 
            'olshop', 
            'tiktok', 
            'yt');
            
        $values = array(
            $fb, 
            $ig, 
            $olshop, 
            $tiktok, 
            $yt);
        
        $res = $this->db->update(
            'account', 
            $fields, 
            $values, 
            ' id = '.$id);
        
        if(isset($res)) {
            return array(
                'status'=> true, 
                'data'=> array(
                    'fb'=> $fb, 
                    'ig'=> $ig, 
                    'olshop'=> $olshop, 
                    'tiktok'=> $tiktok, 
                    'yt'=> $yt)
                );
        } else {
            return array(
                'status'=> false, 
                'msg'=> 'Gagal menyimpan');
        }
    }
    
    public function getOtherInfo($id) {
        $accounts = $this->db->selectColumns(
            array(
                'fb', 
                'ig', 
                'olshop', 
                'tiktok', 
                'yt'), 
            'account', 
            'id = ?', 
            array($id));
            
        $account = $accounts[0];
        
        if($account) {
            return array(
                'result'=> true, 
                'data'=> array(
                    'fb'=> $account['fb'],
                    'ig'=> $account['ig'],
                    'olshop'=> $account['olshop'],
                    'tiktok'=> $account['tiktok'],
                    'yt'=> $account['yt'])
                );
        } else {
            return array(
                'result'=> false, 
                'msg'=> 'User tidak ditemukan.');
        }
    }
    
    public function updateUser(
        $id, 
        $wa, 
        $email, 
        $name, 
        $occupation, 
        $company, 
        $province, 
        $city, 
        $bank, 
        $accountname, 
        $accountnumber) {
            
        $fields = array(
            'wa', 
            'email', 
            'fullname', 
            'occupation',
            'company', 
            'province', 
            'city', 
            'bank_name', 
            'bank_account_name', 
            'bank_account_number');
            
        $values = array(
            $wa, 
            $email,
            $name, 
            $occupation,
            $company, 
            $province, 
            $city, 
            $bank, 
            $accountname, 
            $accountnumber);
        
        $account = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id);
        
        return $account;
    }
    
    public function setUserActive($id, $status) {
        $fields = array('active');
        $values = array($status);
        $user = $this->db->update(
            'account', 
            $fields, 
            $values, 
            ' id = '.$id);
        
        return $user;
    }
    
    public function updatePassword($id, $password) {
        $fields = array('password');
        $values = array(hash('sha256', $password));
        $row = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id);
        
        return $row;
    }
    
    public function getSlider() {
        $images = $this->db->selectColumns(
            array(
                'id', 
                'name', 
                'context', 
                'dateupdate'), 
            'home_images', 
            'status = ? LIMIT 4', 
            array(1));
        
        if($images) {
            return array('status'=> true, 'images'=> $images);
        } else {
            return array('status'=> false, 'msg'=> 'Tidak ada yang ditampilkan.');
        }
    }
    
    public function insertLogAdmin($id, $type, $content) {
        $fields = array('id_admin', 'log_type', 'log_content', 'date');
        $values = array($id, $type, $content, date('Y-m-d h:i:s'));
        $this->db->insert('log_admin', $fields, $values);
    }
    
    public function lenAccountNotVerifiedExpired($payment_code) {
        $query = 'SELECT 
            count(1) AS lenaccount 
                FROM 
                    account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE 
                    payment_code.code LIKE ? AND 
                    account.verified = 0 AND 
                    account.is_deleted = 0 AND 
                    account.datecreated < NOW() - INTERVAL 2 DAY';
        
        $length = $this->db->rawQueryType('select', $query, array($payment_code));
        
        return (int)$length[0]['lenaccount'];
    }
    
    public function lenAccountNotVerified($payment_code) {
        $query = 'SELECT 
            count(1) AS lenaccount 
                FROM 
                    account INNER JOIN payment_code ON account.id = payment_code.id 
                WHERE 
                    payment_code.code LIKE ? AND 
                    account.verified = 0 AND 
                    account.is_deleted = 0 AND 
                    account.datecreated >= NOW() - INTERVAL 2 DAY';
        
        $length = $this->db->rawQueryType('select', $query, array($payment_code));
        
        return (int)$length[0]['lenaccount'];
    }
    
    // star reward component
    public function starCount($id) {
        $users = $this->db->selectColumns(array('star', 'id'), 'account', 'id = ?', array($id));
        $user = $users[0];
        if($user) {
            return $user['star'];
        } else {
            return 0;
        }
    }
    
    public function updateDateTimeAds($id) {
        $datetimenow    = date('Y-m-d H:i:s');
        $fields = array('last_watch_ads');
        $values = array($datetimenow);
        $row = $this->db->update(
            'account', 
            $fields, 
            $values, 
            'id = '.$id);
            
        return $row;
    }
    
    // star reward component
    public function updateStar($id) {
        $gen_list = $this->db->selectColumns(array('gen_1', 'gen_2', 'gen_3', 'gen_4', 'gen_5'))[0];
        $gens = array($id, $gen_list['gen_1'], $gen_list['gen_2'], $gen_list['gen_3'], $gen_list['gen_4'], $gen_list['gen_5']);
        
        $query = 'UPDATE account 
        SET `star` = (`star` + 1) WHERE id IN('.implode(',', $gens).')';
        
        $rows = $this->db->rawQuery($query, array());
        
        return $rows > 0 && $this->updateDateTimeAds($id);
    }
    
    public function getListCountUserVerified($maxverified, $date_start, $date_end) {
        $query = "SELECT * FROM (SELECT id_created, count(id) as countverified FROM `account` WHERE verified = 1 AND date_verified BETWEEN ? AND ? GROUP BY id_created) AS data WHERE countverified >= ? ORDER BY countverified DESC";
        
        $rows = $this->db->rawQuery($query, array($date_start, $date_end, $maxverified));
        
        return $rows;
    }
    
    public function lenContact($where = '', $value = array()) {
        return $this->db->recordCount(
            'contact',
            $where,
            $value);
    }
    
    public function lenGen($where = '', $value = array()) {
        return $this->db->recordCount(
            'account_generation', 
            $where, 
            $value);
    }
    
    public function len($where = '', $value = array()){ // count rows of admin table
        return $this->db->recordCount(
            'account', 
            $where, 
            $value);
    }
}