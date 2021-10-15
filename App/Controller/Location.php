<?php
namespace App\Controller;
use System\Controller as Controller;

class Location extends Controller{

    public function __construct(){
        if(!$this->req->session->userdata('islogged')){
            return $this->res->redirect('');
        }
        
    }

    public function index(){
        if($this->req->getMethod() === 'GET')
            $this->res->render('adminpage/location', array('title'=>'Location', 'scripts'=>array($this->temp->public.'js/custom/location.js')));
    }
    
    public function insert(){
        if($this->req->getMethod() === 'POST'){
            $locationname = $this->req->Post('loc_name');

        }
    }

    public function edit(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
            $locationname = $this->req->Post('loc_name');

        }
    }

    public function delete(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
        }
    }

    // public function locationList(){
    //     if($this->req->getMethod() === 'GET'){
    //         $search = $this->req->Get('search');
    //         $orderby = $this->req->Get('order');
    //         $sort = $this->req->Get('sort');
    //         $page = $this->req->Get('page');
    //         $rows = $this->req->Get('rows');
            
    //     }
    // }
    
    public function locationlist(){ // mengambil data lokasi
        if($this->req->session->userdata('islogged')){
            if($this->req->getMethod() === 'POST'){
                $url = $this->req->urlmain;
                $requestData= $_REQUEST;
                $columns = array( 
                // datatable column index  => database column name
                    0 => 'location_name',
                );

                $where = '';
                $value = array();
                $search = $requestData['search']['value'];
                if($search != ''){
                    $where = 'name LIKE ? OR username LIKE ?';
                    $value = array('%'.$search.'%', '%'.$search.'%');
                }
                $totalRecord = $this->admin->len();
                $totalFilter = $this->admin->len($where, $value);
                $user = $this->admin->listTable($requestData['length'], $requestData['start'], $columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir'], $search);

                $data = array();
                foreach ($user as $key => $value) {
                    $nestedData = array();
                    $nestedData['name'] = $value['name'];
                    $nestedData['username'] = $value['username'];
                    $id = $value['id'];
                    $href = $url.'user/editUser?id='.$id;
                    $nestedData['tools'] = "<td>
                                                <center>
                                                    <div id='thanks'>
                                                        <a data-placement='bottom' data-toggle='tooltip' title='Edit Admin' href='$href'>
                                                            <i class='material-icons'>mode_edit</i>
                                                        </a>  
                                                        <a class='mdl' data-name='".$value['name']."' data-id='$id' data-placement='bottom' title='Hapus Admin' href='javascript:void(0);'>
                                                            <i class='material-icons'>delete</i>
                                                        </a>
                                                    </div>
                                                </center>
                                            </td>";
                    $data[] = $nestedData;
                }
                $json_data = array(
                    'draw'=>intval($requestData['draw']),
                    'recordsTotal'=> intval($totalRecord),
                    'recordsFiltered'=> intval($totalFilter),
                    'data'=> $data
                );
                $this->res->json($json_data);

                echo '';
            }
        }
    }
}