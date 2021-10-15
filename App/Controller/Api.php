<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\User as User;
use App\Model\Province as Province;
use App\Model\Bank as Bank;
use App\Model\City as City;
use App\Model\Wallet as Wallet;
use App\Model\Withdraw as Withdraw;

class Api extends Controller{
	public function __construct(){
        $result = $this->jwt->authenticated('helloworld');
        header('Content-type: application/json');
        
        if(!$result['status']) {
            exit($this->res->json(array('status'=>false, 'msg'=> $result['msg'])));
        } else {
            $this->loadModel('user', new User());
            $this->loadModel('wallet', new Wallet());
            $this->loadModel('withdraw', new Withdraw());
        }
    }

    // menyetujui term and condition
    public function tac() {
        $json_message = array('status'=>false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $row    = $this->user->agreeTAC($id);
            
            if($row > 0) {
                $json_message = array(
                    'status'=>true,
                    'tac_agreement'=> true
                );
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function profile() {
        $json_message = array('status'=>false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            
            $result = $this->user->getAccount($id);
            
            if($result['status']) {
                $account = $result['account'];
                $json_message = array(
                    'status'=> true, 
                    'data'=> $account);
            }else {
                $msg = $result['msg'];
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $msg);
            }
            
            return $this->res->json($json_message);
        }
    }
    
    public function editinfo() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $fb = $this->req->Post('fb');
            $ig = $this->req->Post('ig');
            $os = $this->req->Post('os');
            $tt = $this->req->Post('tt');
            $yt = $this->req->Post('yt');
            
            $result = $this->user->updateInfo(
                $id, 
                $fb, 
                $ig, 
                $os, 
                $tt, 
                $yt);
                
            if($result['status']) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> $result['data']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $result['msg']);
            }
        }
        return $this->res->json($json_message);
    }
    
    public function synccontacts() {
        $json_message = array('status'=>false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id         = $this->req->Post('id');
            $count      = $this->req->Post('synccount');
            $lastsync   = $this->req->Post('lasysync');
            
            $contacts = $this->user->getContactsRandom($id, $count, $lastsync);
            
            if($contacts['status']) {
                $json_message = array(
                    'status'=> true, 
                    'contacts'=> $contacts['contact'],
                    'last_sync'=> $contacts['last_date_sync']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $contacts['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }

    // sinkornasi kontak baru
    public function synccontact() {
        $json_message = array('status'=>false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $contacts = $this->user->getContactRandom($id);
            
            if($contacts['status']) {
                $json_message = array(
                    'status'=> true, 
                    'contacts'=> $contacts['contact']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $contacts['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function contactrecovery() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahn');
        
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
            $referal_request = $this->user->GetReferalById($id);
            
            if($referal_request['return']) {
                $referal = $referal_request['referal'];
                $json_message = array(
                    'status'=> true, 
                    'referal'=> $referal);
            } else {
                $json_message = array('status'=> false);
            }
        }
        
        return $this->res->json($json_message);
    }

    // sinkronasi semua kontak yang didapatkan
    public function syncallcontact() {
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');

            $contacts = $this->user->getContactRandom($id);

            if($contacts['status']) {
                $json_message = array(
                    'status'=> true, 
                    'contacts'=> $contacts['contact']);
            }
        }
        
        return $this->res->json($json_message);
    }

    // mendapatkan personal info
    public function getpersonalinfo() {
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id             = $this->req->Post('id');
            $contacts       = $this->user->getPersonalAccount($id);
            $datetimenow    = date('Y-m-d H:i:s');
            
            if($contacts['status']) {
                $json_message = array(
                    'status'=> true, 
                    'account'=> $contacts['account'],
                    'lasttimeads' => $contacts['datetimeads'],
                    'currenttime' => $datetimenow,
                    'version_code'=> 10,
                    'version_name'=> '1.0.5',
                    'version_status'=> 0);
            }
        }
        
        return $this->res->json($json_message);
    }

    // membuat partner
    public function createpartner() {
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id         = $this->req->Post('idcreated');
            $wa         = $this->req->Post('wa');
            $email      = $this->req->Post('email');
            $name       = $this->req->Post('name');
            $gender     = $this->req->Post('gender');
            $occupation = $this->req->Post('occupation');
            $company    = $this->req->Post('company');
            $province   = $this->req->Post('province');
            $city       = $this->req->Post('city');
            $bank       = $this->req->Post('bank');
            $bank_number= $this->req->Post('bank_number');
            $bank_name  = $this->req->Post('bank_name');
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
            }else if($id <= 0) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Terjadi kesalahan');
            }else if($lenwa < 7 || $lenwa > 15) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Nomor telepon tidak valid.');
            }else if($password !== $repassword) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'password tidak sama');
            }else {
                $result = $this->user->InsertContact(
                    $id, 
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
                    $password);
    
                if($result['return']){
                    $json_message = array(
                        'status'=> true, 
                        'data'=> $result['data']);
                }else{
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> $result['msg'], 
                        'id'=> $result['id']);
                }
            }
        }
        
        $this->res->json($json_message);
    }
    
    public function generation() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $generation_list = $this->user->generationList($id);
            
            if($generation_list) {
                $json_message = array(
                    'status'=> true, 
                    'type'=> 0, 
                    'generation'=> $generation_list);
            }
        }
            
        return $this->res->json($json_message);
    }
    
    public function withdrawhistory() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $index  = $this->req->Get('index');
            $limit  = $this->req->Get('limit');
            
            $history_list = $this->withdraw->getWithdrawHistory(
                $id, 
                ($index - 1) * $limit, 
                $limit);
            
            if($history_list['status']) {
                $json_message = array(
                    'status'=> true, 
                    'histories'=> $history_list['rows']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $history_list['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function pendingaccount() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $search = $this->req->Post('search');
            $index  = $this->req->Get('index');
            $limit  = $this->req->Get('limit');
            
            $pending_list = $this->user->getPending(
                $search, 
                $id, 
                ($index - 1) * $limit, 
                $limit);
            
            if($pending_list['status']) {
                $json_message = array(
                    'status'=> true, 
                    'accounts'=> $pending_list['rows']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $pending_list['msg']);
            }
        }
        return $this->res->json($json_message);
    }
    
    public function generationlistaccount() {
        $json_message = array('status'=> true, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $gen_id = $this->req->Post('genid');
            $search = $this->req->Post('search');
            $index  = $this->req->Get('index');
            $limit  = $this->req->Get('limit');
            
            $account_list = $this->user->accountListGeneration(
                $id, 
                $gen_id,
                $search, 
                ($index - 1) * $limit,
                $limit);
            
            if(isset($account_list)) {
                $json_message = array(
                    'status'=> true, 
                    'partners'=> $account_list);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'tidak ada data.');
            }
        }
        
        return $this->res->json($json_message);
    }

    // mendapatkan partner
    public function partner() {
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $index  = $this->req->Get('index');
            $limit  = $this->req->Get('limit');
            
            $partners = $this->user->GetPartner(
                $id, 
                ($index - 1) * $limit, 
                $limit);

            if($partners['status']) {
                $json_message = array(
                    'status'=> true, 
                    'partners'=> $partners['rows']);
            }else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $partners['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function detailbonusgeneration() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $genid  = $this->req->Post('genid');
            $index  = $this->req->Get('index');
            $limit  = $this->req->Get('limit');
            
            $wallet_list = $this->wallet->getDetailBonusGeneration(
                $id, 
                $genid, 
                ($index - 1) * $limit,
                $limit);
            
            if($wallet_list['return']) {
                $json_message = array(
                    'status'=> true, 
                    'detailbonus'=> $wallet_list['list']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $wallet_list['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function bonusgeneration() {
        $json_message = array(
            'status'=> false, 
            'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            
            $bonus_generation = $this->wallet->getAllBonusGeneration($id);
            
            if($bonus_generation) {
                $json_message = array(
                    'status'=> true, 
                    'bonusgenerations'=> $bonus_generation);
            }
        }
        
        return $this->res->json($json_message);
    }

    public function changepassword() {
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id             = $this->req->Post('id');
            $old_password   = $this->req->Post('oldpass');
            $new_password   = $this->req->Post('newpass');
            
            if($old_password != $new_password) {
                $account = $this->user->ChangePassword(
                    $id, 
                    $old_password, 
                    $new_password);
                    
                if($account['status']) {
                    $json_message = array(
                        'status'=> true, 
                        'msg'=> 'Password berhasil diganti.');
                }else{
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> $account['msg']);
                }
            } else{
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Password lama dan baru tidak boleh sama.');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function infototalbonus() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            
            $money = $this->wallet->getCashBackGeneration($id);
            
            if($money['return']) {
                $json_message = array(
                    'status'=> true, 
                    'totalbonus'=> (int)$money['money']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $money['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function requestwithdraw() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id             = $this->req->Post('id');
            $totalwithdraw  = $this->req->Post('totalwithdraw');
            
            $withdraw = $this->withdraw->createWithDraw(
                $id, 
                (int)$totalwithdraw);
            
            if($withdraw['return']) {
                $account = $this->user->getPhoneNumber($id);
                if($account['result']) {
                    $json_message = array(
                        'status'=> true, 
                        'data'=> $withdraw['data']);
                }
            }else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Terjadi kesalahan, withdraw gagal coba ulangi lagi');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function confirmCodeWithdraw() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id             = $this->req->Post("id");
            $id_withdraw    = $this->req->Post("id_withdraw");
            $code_confirm   = $this->req->Post("code");
            
            $confirm_draw = $this->withdraw->confirmWithDraw(
                $id, 
                $id_withdraw, 
                $code_confirm);
            
            if($confirm_draw['return']) {
                $json_message = array(
                    'status'=> true, 
                    'msg'=> $confirm_draw['msg']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $confirm_draw['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function infowithdraw() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            $info = $this->user->getInfoWithdraw($id);
            
            if($info['status']) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> $info['account']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $info['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function otherinfo() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            
            $info = $this->user->getOtherInfo($id);
            
            if($info['result']) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> $info['data']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $info['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function recover() {
        $id = $this->req->Get('id_member');
        if($id) {
            if(file_exists(__dir__."/../../public/recovery/$id.zip")) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename(__dir__."/../../public/recovery/$id.zip").'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize(__dir__."/../../public/recovery/$id.zip"));
                flush(); // Flush system output buffer
                readfile(__dir__."/../../public/recovery/$id.zip");
                die();
            } else {
                http_response_code(404);
                die();
            }
        }
    }
    
    public function totalwithdraw() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            
            $withdraw = $this->withdraw->getTotalWithdraw($id);
            if($withdraw['return']) {
                $json_message = array(
                    'status'=> true, 
                    'total'=> (int)$withdraw['money']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $withdraw['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function totalcashback() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            
            $wallet = $this->wallet->getTotalCashBack($id);
            if($wallet['return']) {
                $json_message = array(
                    'status'=> true, 
                    'total'=> (int)$wallet['money']);
            } else {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> $wallet['msg']);
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
    
    public function savebank() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'POST') {
            $id     = $this->req->Post('id');
            $bank   = $this->req->Post('bank');
            $number = $this->req->Post('bank_number');
            $name   = $this->req->Post('bank_name');
            
            $bank_result = $this->user->updateBankInfo(
                $id, 
                $bank, 
                $name, 
                $number);
            
            if($bank_result['status']) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> $bank_result['data']);
            } else {
                $json_message = array(
                    'status'=> true, 
                    'msg'=> $bank_result['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function slider() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'GET') {
            $images = $this->user->getSlider();
            
            if($images['status']) {
                $result = array();
                foreach($images['images'] as $index => $value) {
                    array_push(
                        $result, 
                        array(
                            'url'=> 'https://rbb.ethel-world.com/public/images/'.$value['name'].'.jpeg', 
                            'ctx'=> $value['context']));
                }
                $json_message = array('status'=> true, 'images'=> $result);
            } else {
                $json_message = array('status'=> false, 'msg'=> $images['msg']);
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function getstar() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            $countStar = $this->user->starCount($id);
            
            $json_message = array('status'=> true, 'star'=> $countStar);
        }
        
        return $this->res->json($json_message);
    }
    
    public function reward() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            
            $reward = $this->user->updateStar($id);
            if($reward) {
                $countStar = $this->user->starCount($id);
                $json_message = array('status'=> true, 'star'=> $countStar);
            }
        }
        
        return $this->res->json($json_message);
    }
}