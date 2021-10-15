<?php
namespace App\Model;
use System\Model as Model;

class Province extends Model{
    public function GetProvince($name) {
        $search = "%".$name."%";
        $list = $this->db->selectColumns(array("id", "name"), "province", " name = ?", array($search));
        return $list;
    }

    public function GetAllProvince() {
        $list = $this->db->selectColumns(array("id", "name"), "province", "ORDER BY name ASC", array());
        return $list;
    }

    public function getAllListProvince($search, $sortby, $sort, $index, $limit) {
        $len_province = $this->len();
        $query = "";
        $province_list = array();
        if(trim($search) != ""){
            $src = "%".trim($search)."%";
            $values = array($src);
            $len_province = $this->len("name LIKE ?", $values);
            $province_list = $this->db->selectColumns(array("id", "name", "iso"), "province", "name LIKE ? ORDER BY $sortby $sort LIMIT $index, $limit", $values);
        }else{
            $province_list = $this->db->selectColumns(array("id", "name", "iso"), "province", " ORDER BY $sortby $sort LIMIT $index, $limit", array());
        }
        return array("countall"=>$len_province, "list"=>$province_list);
    }

    public function createProvince($name, $iso) {
        $fields = array("name", "iso");
        $values = array($name, $iso);
        $rows = $this->db->insert("province", $fields, $values);
        return $rows;
    }
    
    public function updateProvince($id, $name, $iso) {
        $fields = array("name", "iso");
        $values = array($name, $iso);
        $rows = $this->db->update("province", $fields, $values, "id = $id");
        return $rows;
    }

    public function deleteProvince($id) {
        $rows = $this->db->delete("province", "id = ?", array($id));
        return $rows;
    }
    // $query = "";
    // $values = array();
    // if(trim($search) != ""){
    //     $src = "%".trim($search)."%";
    //     $values = array($src, $src);
    //     $query = "SELECT * FROM tbl_sublocation as tsl INNER JOIN tbl_location as tl ON tsl.id_location = tl.id WHERE tl.location_name LIKE ? OR tsl.sublocation_name LIKE ? ORDER BY $sortby $sort LIMIT $index,$rows";
    // }else{
    //     $query = "SELECT * FROM tbl_sublocation as tsl INNER JOIN tbl_location as tl ON tsl.id_location = tl.id ORDER BY $sortby $sort LIMIT $index,$rows";
    // }
    // $sublocation = $this->db->rawQuery($query, $values);
    // return $location;

    public function len($where = "", $value = array()){ // count rows of admin table
        return $this->db->recordCount("province", $where, $value);
    }
}