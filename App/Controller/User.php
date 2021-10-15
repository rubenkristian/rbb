<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\Admin as Admin;
use App\Model\User as UserModel;

class User extends Controller{
    public function __construct(){
        if(!$this->req->session->userdata('islogged')){
            return $this->res->redirect('login');
        } else {
            $this->loadModel('admin', new Admin());
            $this->loadModel('user', new UserModel());
        }
    }

    public function index() {
        if($this->req->getMethod() === 'GET')
            return $this->res->render(
                'adminpage/user', 
                array(
                    'title'=>'User', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/user-list.js')));
    }

    public function insert(){
        if($this->req->getMethod() === 'POST'){
            $wa             = $this->req->Post('wa');
            $name           = $this->req->Post('name');
            $gender         = $this->req->Post('gender');
            $occupation     = $this->req->Post('occupation');
            $company        = $this->req->Post('company');
            $province       = $this->req->Post('province');
            $city           = $this->req->Post('city');
            $bank           = $this->req->Post('bank');
            $bank_number    = $this->req->Post('bank_number');
            $bank_name      = $this->req->Post('bank_name');
            $fb             = $this->req->Post('fb');
            $ig             = $this->req->Post('ig');
            $tiktok         = $this->req->Post('tiktok');
            $olshop         = $this->req->Post('olshop');
            $yt             = $this->req->Post('yt');

            $result = $this->user->InsertCompanyAccount(
                        0, 
                        $name, 
                        $wa, 
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
                        $yt);
            
            if($result['return']){
                $this->res->json(
                    array(
                        'status'=> true, 
                        'data'=> array('msg'=> 'Done')));
            }else{
                $this->res->json(
                    array(
                        'status'=> false, 
                        $result['msg']));
            }
        }
    }

    public function delete(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
        }
    }

    public function list() {
        if($this->req->getMethod() === 'POST') {
            $this->loadListRequest(1, 2);
        }
    }

    public function listnotverified() {
        if($this->req->getMethod() === 'POST') {
            $this->loadListRequest(0);
        }
    }
    
    public function listnotverifiedexpired() {
        if($this->req->getMethod() === 'POST') {
            $this->loadListRequest(4, 4);
        }
    }

    public function verified(){
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        if($this->req->getMethod() === 'POST') {
            $id         = $this->req->Post('id');
            $number     = $this->req->Post('number');
            $password   = $this->req->Post('password');
            $username   = $this->req->Post('username');
            
            $check = $this->user->checkAdmin($username, $password, 3, $id);
            if($check['status']) {
                if(!$this->user->accountVerified((int)$id)) {
                    $res_verified = $this->user->verified($id);
                    if($res_verified['return']) {
                        $json_message = array(
                            'status'=> true,
                            'link' => 'https://api.whatsapp.com/send?phone='.$number.'&text=Selamat bergabung di aplikasi RBB, akun anda telah aktif.%0a%0aUsername : '. $res_verified['referal'].'%0a%0aUsername juga berfungsi sebagai nomor referral anda, silahkan sebarkan no referral anda untuk mendapatkan cashback Rp 10rb setiap terjadi validasi akun baru menggunakan referensi anda.');
                    } else {
                        $json_message = array(
                            'status'=> false,
                            'msg'=> 'Verifikasi gagal.');
                    }
                } else {
                    $json_message = array('status'=> false, 'msg'=> 'akun telah terverifikasi.');
                }
            }
        }
        return $this->res->json($json_message);
    }

    public function notverifieds() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/list-not-verified', 
                array(
                    'title'=>'User', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/user-list-notverified.js'
                    )
                )
            );
        }
    }
    
    public function notverifiedexpired() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/list-not-verified-expired',
                array(
                    'title'=> 'Not Verified Expired',
                    'scripts'=> array(
                        $this->temp->public.'js/custom/user-list-notverifiedexpired.js'
                        )
                    )
                );
        }
    }

    public function company() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/list-account-company', 
                array(
                    'title'=>'User', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/company-list.js'
                    )
                )
            );
        }
    }
    
    public function companyedit() {
        if($this->req->getMethod() === 'GET') {
            
        }
    }

    public function companylist() {
        if($this->req->getMethod() === 'POST') {
            $this->loadListRequest(2);
        }
    }

    public function detail() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
    
            $user = $this->user->getAccount($id);
            $generation_list = $this->user->generationList($id);
    
            if($user['status']) {
                // var_dump($user['account']);
                return $this->res->render(
                    'adminpage/detail-user', 
                    array(
                        'title'=>'User', 
                        'account'=>$user['account'],
                        'generations'=> $generation_list));
            }
        }
    }

    public function detailcompany() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
    
            $user = $this->user->getAccount($id);
    
            if($user['status']) {
                // var_dump($user['account']);
                return $this->res->render(
                    'adminpage/detail-user', 
                    array(
                        'title'=>'User', 
                        'account'=>$user['account']));
            }
        }
    }

    public function detailnotverified() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
    
            $user = $this->user->getAccount($id);
            
            if($user['status']){
                $account = $user['account'];
                $num_length = strlen((string)$id);
            
                $id_member = 'rbb';
                switch($num_length){
                    case 1:
                        $id_member .= '000'.$id; 
                    break;
                    case 2:
                        $id_member .= '00'.$id;
                    break;
                    case 3:
                        $id_member .= '0'.$id;
                    break;
                    default:
                        $id_member .= $id;
                    break;
                    
                }
                
                return $this->res->render(
                    'adminpage/detail-not-verified-user', 
                    array(
                        'title'=>'User', 
                        'id_user'=> $id, 
                        'scripts'=>array(
                            $this->temp->public.'plugins/sweetalert/sweetalert.min.js',
                            $this->temp->public.'js/custom/not-verified.js'),
                        'account'=>$account,
                        'referal'=>$id_member));
            }
        }
    }

    private function loadListRequest($type, $mode = 1) {
        $url            = $this->req->urlmain;
        $requestData    = $_REQUEST;
        $columns        = array(
                            0=> 'account.id',
                            1=> 'id_created',
                            2=> 'fullname',
                            3=> 'wa',
                            4=> 'code'
                        );
        $search     = $requestData['search']['value'];
        $sort       = $requestData['order'][0]['dir'];
        $sort_by    = $columns[$requestData['order'][0]['column']];
        $index      = $requestData['start'];
        $limit      = $requestData['length'];

        $page = ($index - 1) * $limit;
        $users = array();

        switch($type) {
            case 0:
                $users = $this->user->getListNotVerifiedAccount(
                    $search, 
                    $sort, 
                    $sort_by, 
                    $index, 
                    $limit);
            break;
            case 1:
                $users = $this->user->getListVerifiedAccount(
                    $search, 
                    $sort, 
                    $sort_by, 
                    $index,
                    $limit);
            break;
            case 2:
                $users = $this->user->getListCompanyAccount(
                    $search, 
                    $sort,
                    $sort_by,
                    $index,
                    $limit);
            break;
            case 3:
                $users = $this->user->getListNonActiveAccount(
                    $search, 
                    $sort, 
                    $sort_by, 
                    $index, 
                    $limit);
            break;
            case 4:
                $users = $this->user->getListExpiredNotVerifiedAccount(
                    $search,
                    $sort,
                    $sort_by,
                    $index,
                    $limit);
            break;
            default:
            return;
        }
    
        
        $data = array();
        
        foreach ($users['list'] as $key => $value) {
            $nestedData = array();
            $id         = $value['id'];
            
            $num_length = strlen((string)$id);
            $id_member  = 'rbb';
            
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
            
            if($type !== 2) {
                $referral_id = $value['id_created'];
                $num_length = strlen((string)$referral_id);
                $id_created  = 'rbb';
                
                switch ($num_length) {
                    case 1:
                        $id_created = $id_created.'000'.$referral_id;
                        break;
                    case 2:
                        $id_created = $id_created.'00'.$referral_id;
                        break;
                    case 3:
                        $id_created = $id_created.'0'.$referral_id;
                        break;
                    default:
                        $id_created = $id_created.$referral_id;
                        break;
                }
                
                $nestedData['id_created'] = '<span style="color:#0083ff">'.$id_created.'</span>';
            }
            
            $nestedData['id']       = $id_member;
            $nestedData['fullname'] = $value['fullname'];
            $nestedData['wa']       = '<span class="phonenumberitem">'.$value['wa'].'</span>';
            
            if($type === 0 || $type === 4) {
                $nestedData['code'] = $value['code'];
            }
            
            $href = $url.'user/detail?id='.$id;
            
            if($type === 0) {
                $href = $url.'user/detailnotverified?id='.$id;
            }
            
            if($mode === 1) {
                $nestedData['tools'] = '<td><center><div id=\'thanks\'><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' href=\''.$href.'\'><i class=\'material-icons\'>visibility</i></a><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' id="deleteShow" data-iddelete="'.$id.'"><i class=\'material-icons\'>delete</i></a></div></center></td></div></center></td>';
            } else if($mode === 2){
                $nestedData['tools'] = '<td><center><div id=\'thanks\'><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' href=\''.$href.'\'><i class=\'material-icons\'>visibility</i></a><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' id="modalshow" data-iddelete="'.$id.'"><i class=\'material-icons\'>delete</i></a></div></center></td>';
            } else if($mode === 3) {
                $nestedData['tools'] = '<td><center><div id=\'thanks\'><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' id="modalshow" data-id="'.$id.'"><i class=\'material-icons\'>undo</i></a><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' id="deleteShow" data-iddelete="'.$id.'"><i class=\'material-icons\'>delete</i></a></div></center></td>';
            } else {
                $nestedData['tools'] = '<td><center><div id=\'thanks\'><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' id="updateShow" data-idupdate="'.$id.'"><i class=\'material-icons\'>autorenew</i></a><a data-placement=\'bottom\' data-toggle=\'tooltip\' title=\'Detail\' id="deleteShow" data-iddelete="'.$id.'"><i class=\'material-icons\'>delete</i></a></div></center></td>';
            }
            $data[] = $nestedData;
        }
        $count = count($data);
        return $this->res->json(array('draw'=>intval($requestData['draw']), 'recordsTotal'=> $count, 'recordsFiltered'=> $users['countall'], 'data'=> $data));
    }
    
    public function changestatus() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
        }
    }
    
    public function listnonactive() {
        if($this->req->getMethod() === 'POST') {
            $this->loadListRequest(3, 3);
        }
    }
    
    public function edit() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            $account = $this->user->getAccount($id);
            // var_dump($account);
            if($account['status']) {
                return $this->res->render(
                    'adminpage/edit-user', 
                    array(
                        'title'=>'User', 
                        'account'=> $account['account'], 
                        'scripts'=>array(
                            $this->temp->public.'plugins/sweetalert/sweetalert.min.js', 
                            $this->temp->public.'js/custom/edit-user.js')));
            } else {
                echo $account['msg'];
            }
        }
    }
    
    public function editpass() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            $account = $this->user->getAccount($id);
            
            if($account['status']) {
                return $this->res->render(
                    'adminpage/edit-user-password',
                    array(
                        'title'=> 'User', 
                        'account'=> $account['account'], 
                        'scripts'=> array(
                            $this->temp->public.'plugins/sweetalert/sweetalert.min.js', 
                            $this->temp->public.'js/custom/edit-pass-user.js')));
            } else {
                echo $account['msg'];
            }
        }
    }
    
    public function submitedit() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $id             = $this->req->Post('id');
            $wa             = $this->req->Post('wa');
            $email          = $this->req->Post('email');
            $name           = $this->req->Post('name');
            $occupation     = $this->req->Post('occupation');
            $company        = $this->req->Post('company');
            $province       = $this->req->Post('province');
            $city           = $this->req->Post('city');
            $bank           = $this->req->Post('bank');
            $accountname    = $this->req->Post('accountname');
            $accountnumber  = $this->req->Post('accountnumber');
            $username       = $this->req->Post('username');
            $password       = $this->req->Post('password');
            
            $check = $this->user->checkAdmin($username, $password, 9, $id);
            
            if($check['status']) {
                $update_user = $this->user->updateUser(
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
                    $accountnumber);
                    
                if($update_user) {
                    $json_message = array(
                        'status'=> true, 
                        'msg'=> 'Berhasil memperbaharui');
                } else {
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> 'Gagal memperbaharui');
                }
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function suspend() {
        if($this->req->getMethod() === 'GET') {
            
        }
    }
    
    public function submitsuspend() {
        $json_message = array(
            'status'=> false, 
            'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $status = $this->user->setUserActive($id, 0);
            if($status) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Berhasil menonaktifkan user.');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function submitupdate() {
        $json_message = array(
            'status'=> false, 
            'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $status = $this->user->updateAccount($id);
            if($status) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Berhasil up akun.');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function submitdelete() {
        $json_message = array(
            'status'=> false, 
            'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $status = $this->user->deleteAccount($id);
            if($status) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Berhasil menghapus akun.');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function submitactive() {
        $json_message = array(
            'status'=> false, 
            'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $id = $this->req->Post('id');
            $status = $this->user->setUserActive($id, 1);
            if($status) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'Berhasil mengaktifkan user.');
            }
        }
        
        return $this->res->json($json_message);
    }
    
    public function submitchagepassword() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $username       = $this->req->Post('username');
            $password       = $this->req->Post('password');
            $id             = $this->req->Post('id');
            $newpassword    = $this->req->Post('newpassword');
            $newrepassword  = $this->req->Post('newrepassword');
            
            $check = $this->user->checkAdmin($username, $password, 7, $id);
            if($check['status']) {
                if($newpassword !== $newrepassword) {
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> 'masukan password yang sama di kedua input');
                } else {
                    $changepassword = $this->user->updatePassword(
                        $id, 
                        $newpassword);
                        
                    if($changepassword) {
                        $json_message = array(
                            'status'=> true, 
                            'msg'=> 'password berhasil diperbaharui');
                    } else {
                        $json_message = array(
                            'status'=> false, 
                            'msg'=> 'password gagal diperbaharui atau password yang dimasukan sama dengan yang terakhir.');
                    }
                }
            }
        }
        return $this->res->json($json_message);
    }
    
    public function nonactive() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/nonactive-user', 
                array(
                    'title'=>'User', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/nonactive-user-list.js')));
        }
    }
}