<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\Bank as BankModel;

class Bank extends Controller{
    public function __construct(){
        $this->loadModel('bank', new BankModel());
    }

    public function index() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/bank', 
                array(
                    'title'=>'Bank', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/bank.js'
                    )
                )
            );
        }
    }

    public function insert(){
        if($this->req->getMethod() === 'POST'){
            $name = $this->req->Post('name');
            $code = $this->req->Post('code');

            
        }
    }

    public function edit(){
        if($this->req->getMethod() === 'POST'){
            $name = $this->req->Post('name');
            
        }
    }

    public function delete(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
        }
    }

    // send bank list to app
    public function get() {
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            
            $bank = $this->bank->GetAllBank();
            
            if(isset($bank)) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> array('bank'=> $bank));
            }else{
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'tidak ditemukan.');
            }
            
            return $this->res->json($json_message);
        }
    }
    
    public function list() {
        if($this->req->getMethod() === 'POST') {
            $requestData= $_REQUEST;
            $columns = array(
                0=> 'id',
                1=> 'name'
            );
            
            $search     = $requestData['search']['value'];
            $sort       = $requestData['order'][0]['dir'];
            $sort_by    = $columns[$requestData['order'][0]['column']];
            $index      = $requestData['start'];
            $limit      = $requestData['length'];

            $page = ($index - 1) * $limit;

            $banks = $this->bank->dataBank(
                $search, 
                $sort_by, 
                $sort, 
                $index, 
                $limit);
                
            $list = $banks['list'];
            $count = count($list);
            return $this->res->json(
                array(
                    'draw'=>intval($requestData['draw']), 
                    'recordsTotal'=> $count, 
                    'recordsFiltered'=> $banks['countall'],
                    'data'=> $list));
        }
    }

}