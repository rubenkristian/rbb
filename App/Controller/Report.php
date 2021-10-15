<?php
namespace App\Controller;
use System\Controller as Controller;

class Report extends Controller{

    public function __construct(){
        if(!$this->req->session->userdata("islogged")){
            $this->res->redirect("");
        }
    }

    public function index(){
        $this->res->render("adminpage/location", array("title"=>"Perimeter"));
    }
}