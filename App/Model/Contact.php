<?php
namespace App\Model;
use System\Model as Model;

class Contact extends Model{
    private function CheckWANumber($wa){
        return $this->Len("whatsapp = ?", array($wa)) > 0;
    }

    private function ContactAvaiable($id){
        return this->Len("id = ?", array($id)) > 0;
    }

    private function IsOldPassword($id, $password){
        $user = $this->db->selectColumns(array("password"), "contact","id = ?", array($id));
        if(count($user) > 0){
            $hasspass = $user[0]["password"];
            return password_verify($password, $hasspass);
        }
        return false;
    }

    public function ChangePassword($id, $oldPassword, $newPassword){
        if($this->ContactAvaiable($id)){
            if($this->IsOldPassword($oldPassword)){
                $fields = array("password");
                $values = array($newPassword);
                $changed = $this->db->update("contact", $fields, $values, "id = $id");
                return $changed;
            }
        }
        return 0;
    }

    public function UpdateAccount($id, $accountType){
        $fields = array("type_account");
        $values = array($accountType);
        $changed = $this->db->update("contact", $fields, $values, "id = $id");
        return $changed;
    }
    
    public function Len($where = "", $value = array()){ // count rows of admin table
        return $this->db->recordCount("contact", $where, $value);
    }

    public function getAccount($id) {
        $account = $this->db->selectColumns(array("wa", "fullname", "occupation", "company", "province", "city", "tac_agreement"), "account", "id = ?", array($id));
    }
}