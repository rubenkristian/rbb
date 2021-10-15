<?php
namespace App\Library;

class CSRF{
    private $folder;
    public function config($folder){
        $this->folder = __DIR__.'\\'.$folder.'\\';
        if(!file_exists($this->folder)){
            mkdir($this->folder, 0777, true);
        }
    }

    public function token(){
        $token = $this->generateRandomString(20);
        chmod($this->folder, 0777);
        $filename = $this->folder.$token.'.token';
        $handle = fopen($filename,'w+');
        fwrite($handle, "");
        fclose($handle);
        return base64_encode($token);
    }

    public function retoken($token){
        chmod($this->folder, 0777);
        $file = $this->folder.base64_decode($token).".token";
        if(file_exists($file)){
            unlink($file);
            return true;
        }
        return false;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}