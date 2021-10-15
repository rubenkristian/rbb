<?php
namespace App\Model;
use System\Model as Model;

class City extends Model{
    public function GetCities($id_province) {
        $list = $this->db->selectColumns(array("id", "name", "province_id"), "city", " province_id = ? ORDER BY name ASC", array($id_province));
        return $list;
    }

    public function getAllListCity($search, $sortby, $sort, $index, $limit) {
        $len_city = $this->len();
        $query = "";
        $values = array();
        $city_list = array();
        if(trim($search) != ""){
            $src = "%".trim($search)."%";
            $values = array($src);
            $len_city = $this->len("name LIKE ?", $values);
            $query = "SELECT city.id, city.name, city.province_id, province.name as province_name FROM city INNER JOIN province ON city.province_id = province.id WHERE city.name LIKE ? ORDER BY $sortby $sort LIMIT $index, $limit";
            $city_list = $this->db->rawQuery($query, $values);
        }else{
            $query = "SELECT city.id, city.name, city.province_id, province.name as province_name FROM city INNER JOIN province ON city.province_id = province.id ORDER BY $sortby $sort LIMIT $index, $limit";
            $city_list = $this->db->rawQuery($query, array());
        }
        return array("countall"=>$len_city, "list"=>$city_list);
    }

    public function createCity($name, $province_id) {
        $fields = array("name", "province_id");
        $values = array($name, $province_id);
        $rows = $this->db->insert("city", $fields, $values);
        return $rows;
    }
    
    public function updateCity($id, $name, $province_id) {
        $fields = array("name", "province_id");
        $values = array($name, $province_id);
        $rows = $this->db->update("city", $fields, $values, "id = $id");
        return $rows;
    }

    public function deleteCity($id) {
        $rows = $this->db->delete("city", "id = ?", array($id));
        return $rows;
    }

    public function len($where = "", $value = array()){ // count rows of admin table
        return $this->db->recordCount("city", $where, $value);
    }
}