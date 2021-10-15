<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\Withdraw as WithdrawModel;
use App\Model\User as UserModel;

class Withdraw extends Controller {
    public function __construct(){
        if(!$this->req->session->userdata('islogged')){
            return $this->res->redirect('login');
        } else {
            $this->loadModel('withdraw', new WithdrawModel());
            $this->loadModel('user', new UserModel());
        }
    }
    
    public function index() {
        if($this->req->getMethod() === 'GET')
            return $this->res->render(
                'adminpage/withdraw-list', 
                array(
                    'title'=>'User', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/withdraw.js')));
    }

    public function list() {
        if($this->req->getMethod() === 'POST') {
            $requestData= $_REQUEST;
            $columns = array(
                0=> 'id',
                1=> 'cash',
                2=> 'type',
                3=> 'date_created',
                4=> 'id_account',
                5=> 'date_verified'
            );
            $search = $requestData['search']['value'];
            $sort = $requestData['order'][0]['dir'];
            $sort_by = $columns[$requestData['order'][0]['column']];
            $index = $requestData['start'];
            $limit = $requestData['length'];
            
            $withdraw_list = $this->withdraw->getListWithdraw(
                $search, 
                $sort, 
                $sort_by, 
                $index, 
                $limit);
            
            $data = array();
            
            foreach ($withdraw_list['list'] as $key => $value) {
                $nestedData = array();
                $id                             = $value['id'];
                $nestedData['id']               = $id;
                $nestedData['name']             = $value['fullname'];
                $nestedData['cash']             = $value['cash'];
                $nestedData['date_verified']    = $value['date_verified'];
                $nestedData['date_created']     = $value['date_created'];
                $href   = $url.'withdraw/detail?id='.$id;
                $nestedData['tools'] = "<td><center><div id='thanks'><a data-placement='bottom' data-toggle='tooltip' title='Detail' href='$href'><i class='material-icons'>visibility</i></a></div></center></td>";
                $data[] = $nestedData;
            }
            $count = count($data);
            return $this->res->json(
                array(
                    'draw'=>intval($requestData['draw']), 
                    'recordsTotal'=> $count, 
                    'recordsFiltered'=> $withdraw_list['countall'], 
                    'data'=> $data));
        }
    }

    public function listrequest() {
        if($this->req->getMethod() === 'POST') {
            $requestData= $_REQUEST;
            $columns = array(
                0=> 'id',
                1=> 'id_member',
                2=> 'cash',
                3=> 'type',
                4=> 'date_created',
                5=> 'id_account'
            );
            $search     = $requestData['search']['value'];
            $sort       = $requestData['order'][0]['dir'];
            $sort_by    = $columns[$requestData['order'][0]['column']];
            $index      = $requestData['start'];
            $limit      = $requestData['length'];
            
            $withdraw_list = $this->withdraw->getListWithdrawRequest(
                $search, 
                $sort, 
                $sort_by,
                $index, 
                $limit);
            
            $data = array();
            
            foreach ($withdraw_list['list'] as $key => $value) {
                $nestedData = array();
                $id = $value['id'];
                $nestedData['id'] = $id;
                $nestedData['id_member'] = $value['id_member'];
                $nestedData['name'] = $value['fullname'];
                $nestedData['cash'] = $value['cash'];
                $nestedData['date_created'] = $value['date_created'];
                $href = $url.'detail?id='.$id;
                $nestedData['tools'] = "<td><center><div id='thanks'><a data-placement='bottom' data-toggle='tooltip' title='Detail' href='$href'><i class='material-icons'>visibility</i></a></div></center></td>";
                $data[] = $nestedData;
            }
            $count = count($data);
            return $this->res->json(
                array(
                    'draw'=>intval($requestData['draw']), 
                    'recordsTotal'=> $count, 
                    'recordsFiltered'=> $withdraw_list['countall'], 
                    'data'=> $data));
        }
    }

    public function request() {
        if($this->req->getMethod() === 'GET')
            return $this->res->render(
                'adminpage/withdraw-request-list', 
                array(
                    'title'=>'User', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/withdraw-request.js')));
    }

    public function confirm() {
        $json_message = array(
            'status'=> false, 
            'msg'=> 'Terjadi kesalahan.');
            
        if($this->req->getMethod() === 'POST') {
            $id         = $this->req->Post('id');
            $number     = $this->req->Post('number');
            $id_member  = $this->req->Post('idmember');
            $password   = $this->req->Post('password');
            $username   = $this->req->Post('username');
            $check = $this->user->checkAdmin($username, $password, 8, $id);
            if($check['status']) {
                $confirm_withdraw = $this->withdraw->verifiedWithDraw($id);
            
                if($confirm_withdraw) {
                    $json_message = array(
                        'status'=> true, 
                        'msg'=> 'Berhasil diverifikasi.',
                        'link'=> 'https://api.whatsapp.com/send?phone='.$number.'&text=Selamat rekan '.$id_member.' bonus cashback RBB anda sudah masuk ke rekening anda');
                }
            }
        }
        return $this->res->json($json_message);
    }

    public function detail() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
            
            $withdraws = $this->withdraw->getWithdrawDetail($id, 1);
            $withdraw = $withdraws[0];
            
            if($withdraw) {
                // var_dump($user['account']);
                return $this->res->render(
                    'adminpage/detail-request-withdraw', 
                    array(
                        'title'=>'User', 
                        'id_withdraw'=> $id, 
                        'scripts'=>array(
                            $this->temp->public.'plugins/sweetalert/sweetalert.min.js',
                            $this->temp->public.'js/custom/withdraw-confirm.js'), 'withdraw'=>$withdraw));
            }
        }
    }
}

