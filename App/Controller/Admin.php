<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\Admin as AdminModel;

class Admin extends Controller{
	public function __construct(){
        if(!$this->req->session->userdata('islogged')){
            return $this->res->redirect('login');
        } else {
            $this->loadModel('admin', new AdminModel());
        }
    }
    
    // get url
	public function index(){
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/admin-home', 
                array(
                    'title'=>'Admin', 
                    'scripts'=> array(
                        $this->temp->public.'js/custom/home.js'
                    )
                )
            );
        }
    }
    
    public function history() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/list-history', 
                array(
                    'title'=>'Admin', 
                    'scripts'=> array(
                        $this->temp->public.'js/custom/list-history.js'
                    )
                )
            );
        }
    }
    
    public function listhistory() {
        if($this->req->session->userdata('islogged')){
            if($this->req->getMethod() === 'POST') {
                $url = $this->req->urlmain;
                $requestData= $_REQUEST;
                $columns = array( 
                // datatable column index  => database column name
                    0 => 'admin.id',
                    1 => 'admin.username',
                    2 => 'log_admin.date'
                );

                $where = '';
                $value = array();
                $search = $requestData['search']['value'];
                if($search != ''){
                    $where = 'date LIKE ?';
                    $value = array(
                        '%'.$search.'%');
                }
                $totalRecord = $this->admin->lenHistory();
                $totalFilter = $this->admin->lenHistory($where, $value);
                $history = $this->admin->listHistory(
                    $requestData['length'], 
                    $requestData['start'], 
                    $columns[$requestData['order'][0]['column']], 
                    $requestData['order'][0]['dir'], 
                    $search);

                $data = array();
                
                foreach ($history as $key => $value) {
                    $nestedData = array();
                    $nestedData['id']       = $value['id'];
                    $nestedData['name']     = $value['name'];
                    $detail = explode(',', $value['detail']);
                    $content = $detail[0];
                    $ip = $detail[1];
                    $action_id = (int)$value['actionid'];
                    $id_member = 'RBB';
                    
                    if($action_id === 3 || $action_id === 4 || $action_id === 5 || $action_id === 6 || $action_id === 7 || $action_id === 9) {
                        $num_length = strlen($content);
                        
                        switch ($num_length) {
                            case 1:
                                $content = $id_member.'000'.$content;
                                break;
                            case 2:
                                $content = $id_member.'00'.$content;
                                break;
                            case 3:
                                $content = $id_member.'0'.$content;
                                break;
                            default:
                                $content = $id_member.$content;
                                break;
                        }
                    } else if($action_id === 8) {
                        $content = 'id withdraw '.$content;
                    }
                    
                    $nestedData['detail']   = $value['action'].' : '.$content;
                    $nestedData['ip']       = $ip;
                    $nestedData['date']     = $value['date'];
                    
                    $data[] = $nestedData;
                }
                $json_data = array(
                    'draw'=>intval($requestData['draw']),
                    'recordsTotal'=> intval($totalRecord),
                    'recordsFiltered'=> intval($totalFilter),
                    'data'=> $data
                );
                return $this->res->json($json_data);
            }
        }
    }
    
    public function config(){
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/admin', 
                array(
                    'title'=>'Admin Configuration', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/admin.js'
                    )
                )
            );
        }
    }

    public function add(){
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/admin-input', 
                array(
                    'title'=>'Admin Input', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/admin-input.js'
                    )
                )
            );
        }
    }

    public function edit(){
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/admin-input', 
                array(
                    'title'=>'Admin Input',
                    'scripts'=>array(
                        $this->temp->public.'js/custom/admin-input.js'
                    )
                )
            );
        }
    }
    
    public function changepass() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/admin-change-password', 
                array(
                    'title'=>'Admin Input', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/admin-change-password.js'
                    )
                )
            );
        }
    }

    public function logout(){
        $this->req->session->destroy();
        return $this->res->redirect('');
    }
    
    public function adminlist(){ // mengambil data admin
        if($this->req->session->userdata('islogged')){
            if($this->req->getMethod() === 'POST'){
                $url = $this->req->urlmain;
                $requestData= $_REQUEST;
                $columns = array( 
                // datatable column index  => database column name
                    0 => 'name',
                    1 => 'username'
                );

                $where = '';
                $value = array();
                $search = $requestData['search']['value'];
                if($search != ''){
                    $where = 'name LIKE ? OR username LIKE ?';
                    $value = array(
                        '%'.$search.'%', 
                        '%'.$search.'%');
                }
                $totalRecord = $this->admin->len();
                $totalFilter = $this->admin->len($where, $value);
                $user = $this->admin->listTable(
                    $requestData['length'], 
                    $requestData['start'], 
                    $columns[$requestData['order'][0]['column']], 
                    $requestData['order'][0]['dir'], 
                    $search);

                $data = array();
                foreach ($user as $key => $value) {
                    $nestedData = array();
                    $nestedData['name']     = $value['name'];
                    $nestedData['username'] = $value['username'];
                    $id     = $value['id'];
                    $href   = $url.'user/editUser?id='.$id;
                    $nestedData['tools'] = "<td><center><div id='thanks'><a data-placement='bottom' data-toggle='tooltip' title='Edit Admin' href='$href'><i class='material-icons'>mode_edit</i></a><a class='mdl' data-name='".$value['name']."' data-id='$id' data-placement='bottom' title='Hapus Admin' href='javascript:void(0);'><i class='material-icons'>delete</i></a></div></center></td>";
                    $data[] = $nestedData;
                }
                $json_data = array(
                    'draw'=>intval($requestData['draw']),
                    'recordsTotal'=> intval($totalRecord),
                    'recordsFiltered'=> intval($totalFilter),
                    'data'=> $data
                );
                return $this->res->json($json_data);
            }
        }
    }

    public function admininput(){ // menyimpan data yang dikirim kedalam tabel admin
        if($this->req->session->userdata('islogged')){
            if($this->req->getMethod() === 'POST'){
                $username = $this->req->Post('username');
                $password = $this->req->Post('password');
                $name     = $this->req->Post('name');

                $loader = $this->admin->insert($name, $username, $password);
                if($loader['return']){
                    return $this->res->status(200)->json(
                        array(
                            'data' => array(
                                'redirect'=>'dasds')
                            )
                        );
                }else{
                    return $this->res->status(500)->json(
                        array(
                            'error'=> array(
                                'msg'=> $loader['msg']
                            )
                        )
                    );
                }
            }
        }
    }

    public function delete(){ // menghapus data pada tabel admin dengan id
        if($this->req->session->userdata('islogged')){
            if($this->req->getMethod() === 'POST'){
                $id     = $this->req->Post('id');
                $loader = $this->admin->delete($id);
                if($loader){
                    return $this->res->status(200)->json(
                        array(
                            'data'=>array(
                                'status'=>true
                            )
                        )
                    );
                }else{
                    return $this->res->status(500)->json(
                        array(
                            'error'=>array(
                                'msg'=>'Gagal menghapus'
                            )
                        )
                    );
                }
            }
        }
    }

    public function adminupdate(){ // memperbaharui data pada admin
        if($this->req->session->userdata('islogged')){
            if($this->req->getMethod() === 'POST'){
                $id         = $this->req->Post('id');
                $username   = $this->req->Post('username');
                $name       = $this->req->Post('name');
            }
        }

    }

    public function notif() {
        $data = $this->admin->getNotif();

        return $this->res->json($data);
    }
    
    public function list() {
        if($this->req->getMethod() === 'POST') {
            $requestData= $_REQUEST;
            $columns    = array(
                0=> 'id',
                1=> 'username'
            );
            $search     = $requestData['search']['value'];
            $sort       = $requestData['order'][0]['dir'];
            $sort_by    = $columns[$requestData['order'][0]['column']];
            $index      = $requestData['start'];
            $limit      = $requestData['length'];
        }
    }
    
    public function submitedit() {
        if($this->req->getMethod() === 'POST') {
            
        }
    }
    
    public function submitadmin() {
        if($this->req->getMethod() === 'POST') {
            
        }
    }
    
    public function submitchagepassword() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan');
        if($this->req->getMethod() === 'POST') {
            $id             = $this->req->Post('id');
            $oldpassword    = $this->req->Post('oldpassword');
            $newpassword    = $this->req->Post('newpassword');
            $newrepassword  = $this->req->Post('newrepassword');
            
            if($newpassword !== $newrepassword) {
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'masukan password yang sama di kedua input');
            } else {
                $oldpasswordcheck = $this->admin->checkoldpassword(
                    $id, 
                    $oldpassword);
                    
                if($oldpasswordcheck === 1) {
                    $changepassword = $this->admin->updatePassword(
                        $id, 
                        $newpassword);
                        
                    if($changepassword) {
                        $json_message = array(
                            'status'=> true, 
                            'msg'=> 'password berhasil diperbaharui');
                    } else {
                        $json_message = array(
                            'status'=> false, 
                            'msg'=> 'password gagal diperbaharui');
                    }
                } else if($oldpasswordcheck === 0){
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> 'password lama anda tidak sama, harap masukan password lama untuk menganti password');
                } else {
                    $json_message = array(
                        'status'=> false, 
                        'msg'=> 'Terjadi kesalahan');
                }
            }
        }
        
        return $this->res->json($json_message);
    }
}