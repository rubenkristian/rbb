<?php
namespace App\Controller;
use System\Controller as Controller;

class Term extends Controller{
    public function privacy() {
        if($this->req->getMethod() === 'GET') {
            return $this->res->render(
                'adminpage/term-service', 
                array(
                    'title'=>'User'
                )
            );
        }
    }
}