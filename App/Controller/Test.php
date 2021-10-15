<?php

namespace App\Controller;

use System\Controller;
use App\Model\User as User;
// use App\Library\Contact as Contact;
// use App\Library\ContactList as ContactList;
// use App\Library\Google\ByteBuffer as ByteBuffer;
// use App\Library\Google\Constants as Constants;
// use App\Library\Google\FlatbufferBuilder as FlatBufferBuilder;
// use App\Library\Google\Struct as Struct;
// use App\Library\Google\Table as Table;
use ZipArchive;

class Test extends Controller{
    public function __construct() {
        $this->loadModel('user', new User());
    }
    
    public function slider() {
        $json_message = array('status'=> false, 'msg'=> 'Terjadi kesalahan.');
        
        if($this->req->getMethod() === 'GET') {
            $images = $this->user->getSlider();
            
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
    public function index() {
        // var_dump($this->user->checkUser("rbb0013", "starmoon0902"));
        // echo substr("rbb0013", -3);
        // echo (int)substr("rbb0013", -3);
        // echo password_hash("123456789", PASSWORD_BCRYPT, ['cost'=>12]);
        
        // echo $this->user->CheckWANumber("628996291028") ? "true" : "false";
            // $datetime   = date('Ymd');
            // echo $datetime;
        // $fbb = new FlatBufferBuilder(1);
        // $contacts = array();
        // $names = array('Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat','Ruben Kristian', 'Rendra', 'Malik','Tio Putra D', 'Arief NurHidayat', 'Aditia Ardana', 'Oris Syaputra', 'Erik MajuHutabarat');
        
        // foreach($names as $index => $value) {
        //     $id_mem = $fbb->createString('rbb0001');
        //     $number = $fbb->createString('628996291028');
        //     $name = $fbb->createString($value);
        //     $occupation = $fbb->createString('Programmer');
        //     $company = $fbb->createString('Weita');
        //     $province = $fbb->createString('Banten');
        //     $city = $fbb->createString('Tangerang');
            
        //     $contact = Contact::createContact($fbb, $index, $id_mem, $number, $name, $occupation, $company, $province, $city);
        //     // $contact = array('id'=> $index, 'mem'=> 'rbb0001', 'num'=>'628996291028', 'name'=> $value, 'occupation'=> 'Programmer', 'company'=> 'Weita', 'province'=> 'Banten', 'city'=> 'Tangerang');
            
        //     array_push($contacts, $contact);
        // }
        
        // // $this->res->json($contacts);
        // $contact_list = ContactList::createListVector($fbb, $contacts);
        // ContactList::startContactList($fbb);
        // ContactList::addList($fbb, $contact_list);
        // $contact_data = ContactList::EndContactList($fbb);
        // $fbb->finish($contact_data);
        
        // $byte = $fbb->sizedByteArray();
        // $buffer = ByteBuffer::wrap($byte);
        // // echo 'hello';
        // // var_dump($fbb->dataBuffer());
        // echo $byte;
        // $zip = new ZipArchive;
        // if ($zip->open('data.zip', ZipArchive::CREATE) === TRUE)
        // {
        //     $zip->addFromString('contact2.txt', $byte);
        // }
        // // $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
        // // fwrite($myfile, $byte);
        // if(file_exists('rbb0008.zip')) {
        //     $yourfile = "/path/to/some_file.zip";
        
        //     $file_name = basename('rbb0008.zip');
        
        //     header("Content-Type: application/zip");
        //     header("Content-Disposition: attachment; filename=rbb0008.zip");
        //     header("Content-Length: " . filesize('rbb0008.zip'));
        
        //     readfile('rbb0008.zip');
        //     exit;
        // } else {
        //     echo 'file no exists';
        // }
    }
    
    public function zip() {
        $zip = new ZipArchive;
        if ($zip->open('recovery/data.zip', ZipArchive::CREATE) === TRUE)
        {
            $zip->addFromString('contact2.txt', $byte);
            echo "yes";
        } else {
            echo "no";
        }
    }
    
    public function read() {
        if(file_exists(__dir__."/../../public/recovery/data.zip")) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename(__dir__."/../../public/recovery/data.zip").'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(__dir__."/../../public/recovery/data.zip"));
            flush(); // Flush system output buffer
            readfile(__dir__."/../../public/recovery/data.zip");
            die();
        } else {
            http_response_code(404);
            die();
        }
    }
    
    public function mono() {
        echo 'mini';
    }
}