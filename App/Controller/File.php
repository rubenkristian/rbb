<?php
namespace App\Controller;
use System\Controller as Controller;

use App\Model\File as FileModel;

class File extends Controller {
    public function __construct() {
        $this->loadModel('file', new FileModel());
    }
    
    public function index() {
        if($this->req->getMethod() === 'GET') {
            // $token = $this->req->Get('token');
            
    		header('Location: https://rbb-world.com/document.html');
    		exit();
            // $download_files = $this->file->getDocumentsList();
            
            // return $this->res->render(
            //     'adminpage/file-list', 
            //     array(
            //         'title'=>'Document download', 
            //         'documents'=> $download_files));
        }
    }
    
    public function document() {
        $json_message = array('status'=> dalse, 'msg'=> 'Terjadi kesalahan.');
        if($this->req->getMethod() === 'GET') {
            $allowed_domains = ['https://rbb-world.com'];

            if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_domains)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }
            $document = $this->file->getDocuments();
            
            if($document['status']) {
                $result = array();
                foreach($document['documents'] as $index => $value) {
                    array_push(
                        $result, 
                        array(
                            'name'=> $value['name'],
                            'url'=> 'https://rbb.ethel-world.com/public/documents/'.$value['type_extension'].'/'.$value['filename'].'.'.$value['type_extension']
                            )
                        );
                }
                $json_message = array('status'=> true, 'documents'=> $result);
            } else {
                $json_message = array('status'=> false, 'msg'=> $images['msg']);
            }
        }
        return $this->res->json($json_message);
    }
    
    public function promo() {
        $json_message = array('status'=> dalse, 'msg'=> 'Terjadi kesalahan.');
        if($this->req->getMethod() === 'GET') {
            $allowed_domains = ['https://rbb-world.com'];

            if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_domains)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }
            $images = $this->file->getPromotion();
            
            if($images['status']) {
                $result = array();
                foreach($images['images'] as $index => $value) {
                    array_push(
                        $result, 
                        array(
                            'url'=> 'https://rbb.ethel-world.com/public/images/'.$value['name'].'.jpeg', 
                            'ctx'=> $value['context']));
                }
                $json_message = array('status'=> true, 'images'=> $result);
            } else {
                $json_message = array('status'=> false, 'msg'=> $images['msg']);
            }
        }
        return $this->res->json($json_message);
    }
}