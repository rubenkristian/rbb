<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\City as CityModel;

class City extends Controller{
    public function __construct(){
        $this->loadModel('city', new CityModel());
    }

    public function index() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/city',
                array(
                    'title'=>'Kota', 
                    'scripts'=> array(
                        $this->temp->public.'js/custom/city-list.js'
                    )
                )
            );
        }
    }

    public function insert(){
        if($this->req->getMethod() === 'POST'){
            $name = $this->req->Post('name');
            $province_id = $this->req->Post('id_province');
            
        }
    }

    public function edit(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
            $name = $this->req->Post('name');
            $province_id = $this->req->Post('id_province');
            
        }
    }

    public function delete(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
        }
    }
    
    // mendaptkan list city
    public function get(){
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('idprovince');

            $cities = $this->city->GetCities($id);
            
            if(isset($cities)) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> array('cities'=> $cities));
            }else{
                $json_message = array(
                    'status'=> false, 
                    'msg'=> 'tidak ditemukan.');
            }
            
            return $this->res->json($json_message);
        }
    }
    
    // mendaptkan list city
    public function getajax(){
        $json_message = array('status'=> false, 'msg'=> 'terjadi kesalahan');
        
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('idprovince');

            $cities = $this->city->GetAjaxCities($id);
            
            if(isset($cities)) {
                $json_message = array(
                    'status'=> true, 
                    'data'=> array('cities'=> $cities));
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
                1=> 'name',
                2=> 'province.name'
            );
            
            $search     = $requestData['search']['value'];
            $sort       = $requestData['order'][0]['dir'];
            $sort_by    = $columns[$requestData['order'][0]['column']];
            $index      = $requestData['start'];
            $limit      = $requestData['length'];

            $page = ($index - 1) * $limit;

            $city = $this->city->getAllListCity(
                $search,
                $sort_by,
                $sort, 
                $index,
                $limit);
            
            $data = array();
            
            foreach ($city['list'] as $key => $value) {
                $nestedData = array();
                $id = $value['id'];
                $nestedData['id']               = $id;
                $nestedData['name']             = $value['name'];
                $nestedData['province_name']    = $value['province_name'];
                $data[] = $nestedData;
            }
            $count = count($data);
            return $this->res->json(
                array(
                    'draw'=>intval($requestData['draw']), 
                    'recordsTotal'=> $count, 
                    'recordsFiltered'=> $city['countall'], 
                    'data'=> $data));
        }
    }

}