<?php

namespace App\Model;
use System\Model as Model;

class Location extends Model{
    public function insert($location){ // insert into location tabel
        $fields = array("location_name");
        $values = array($location);
        $lastid = $this->db->insert("location_tabel", $fields, $values);
        return $lastid;
    }

    public function update($id, $location){ // update rows from location tabel
        $fields = array("location_name");
        $values = array($location);
        $update = $this->db->update("location_tabel", $fields, $values, "id = ".$id);
        return $update;
    }

    public function delete($id){ // to delete rows from location tabel with id location2
        return $this->db->delete("location_tabel", "id = ?", array($id));
    }
}