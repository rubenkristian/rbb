<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\Province as ProvinceModel;

class Province extends Controller{
    public function __construct(){
        $this->loadModel('province', new ProvinceModel());
    }

    public function index() {
        if($this->req->getMethod() === 'GET')
            return $this->res->render(
                'adminpage/province', 
                array(
                    'title'=>'Provinsi', 
                    'scripts'=>array(
                        $this->temp->public.'js/custom/province-list.js')));
    }

    public function insert(){
        if($this->req->getMethod() === 'POST'){
            $name = $this->req->Post('name');
            $iso = $this->req->Post('iso');
            
        }
    }

    public function edit(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
            $name = $this->req->Post('name');
            $iso = $this->req->Post('iso');
            
        }
    }

    public function delete(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
        }
    }
    
    // mendapatkan list province
    public function get(){
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            
            $provinces = $this->province->GetAllProvince();
            
            if(isset($provinces)) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> array('provinces'=> $provinces));
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
            $search = $requestData['search']['value'];
            $sort = $requestData['order'][0]['dir'];
            $sort_by = $columns[$requestData['order'][0]['column']];
            $index = $requestData['start'];
            $limit = $requestData['length'];

            $page = ($index - 1) * $limit;

            $province = $this->province->getAllListProvince($search, $sort_by, $sort, $index, $limit);
            
            $data = array();
            
            foreach ($province['list'] as $key => $value) {
                $nestedData = array();
                $id = $value['id'];
                $nestedData['id'] = $id;
                $nestedData['name'] = $value['name'];
                $nestedData['iso'] = $value['iso'];
                $data[] = $nestedData;
            }
            $count = count($data);
            return $this->res->json(
                array(
                    'draw'=>intval($requestData['draw']), 
                    'recordsTotal'=> $count, 
                    'recordsFiltered'=> $province['countall'], 
                    'data'=> $data));
        }
    }

}