<?php
namespace App\Controller;
use System\Controller as Controller;
use App\Model\Items as ItemsModel;

class Items extends Controller{

    public function __construct(){
        if(!$this->req->session->userdata('islogged')){
            return $this->res->redirect('');
        }
        $this->loadModel('items', new ItemsModel());
    }

    public function index(){
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/location', 
                array(
                    'title'=>'Items'));
        }
    }
    
    public function create(){
        if($this->req->getMethod() === 'POST'){
            $location   = $this->req->Post('location');
            $parameter  = $this->req->Post('parameter');
            $subarea    = $this->req->Post('subarea');
            $item       = $this->req->Post('item');
        }
    }

    public function edit(){
        if($this->req->getMethod() === 'POST'){
            $id         = $this->req->Post('id');
            $location   = $this->req->Post('location');
            $parameter  = $this->req->Post('parameter');
            $subarea    = $this->req->Post('subarea');
            $item       = $this->req->Post('item');
        }
    }

    public function delete(){
        if($this->req->getMethod() === 'POST'){
            $id = $this->req->Post('id');
        }
    }

    public function itemsList(){
        if($this->req->getMethod() === 'GET'){
            $search = $this->req->Get('search');
            $order  = $this->req->Get('order');
            $sort   = $this->req->Get('sort');
            $page   = $this->req->Get('page');
            $rows   = $this->req->Get('rows');
        }
    }
}