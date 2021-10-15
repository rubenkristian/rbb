<?php
namespace App\Controller;
use System\Controller as Controller;

class Wallet extends Controller {
    public function index() {

    }

    public function update() {
        if($this->req->getMethod() === 'POST') {
        }
    }

    public function delete() {
        if($this->req->getMethod() === 'POST') {
        }
    }

    public function list() {
        if($this->req->getMethod() === 'POST') {
            $requestData= $_REQUEST;
            $columns = array(
                0=> 'id',
                1=> 'cash',
                2=> 'type',
                3=> 'date_created'
            );
        }
    }

    public function detail() {
        if($this->req->getMethod() === 'GET') {
            $id = $this->req->Get('id');
        }
    }
}