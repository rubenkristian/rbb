<?php
namespace App\Library;

class Whatsapp{
    private $url;
    private $token;
    
    public function set($url, $token) {
        $this->url = $url;
        $this->token = $token;
    }
    
    public function sendMessage($phoneNumber, $message) {
        $curl = curl_init();
        $token = "";
        $data = [
            'phone' => $phoneNumber,
            'message' => $message,
        ];
        
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                'Authorization: '.$this->token,
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        
        return $result;
    }
    
    public function receiveMessage($id, $phone, $message, $callback) {
        return $callback($id, $phone, $message);
    }
}