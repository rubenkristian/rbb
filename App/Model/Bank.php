<?php
namespace App\Model;
use System\Model as Model;

class Bank extends Model{
    public function GetAllBank() {
        $list = $this->db->selectColumns(
            array(
                'id', 
                'name', 
                'digit_limit'), 
            'bank', 
            'WHERE id != 0 ORDER BY name ASC', 
            array());
        return $list;
    }

    public function GetBank($name) {
        $search = '%'.$name.'%';
        $list = $this->db->selectColumns(
            array(
                'id', 
                'name'), 
            'bank', 
            ' name = ?', 
            array($search));
        return $list;
    }

    public function dataBank(
        $search, 
        $sortby, 
        $sort, 
        $index, 
        $limit) {
        $len_bank = $this->len();
        $bank_list = array();
        if(trim($search) != '') {
            $src = '%'.trim($search).'%';
            $value = array($src);
            $bank_list = $this->db->selectColumns(
                array('id', 'name'), 
                'bank', 
                ' name LIKE ? ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '. $limit, 
                $value);
        } else {
            $bank_list = $this->db->selectColumns(
                array(
                    'id', 
                    'name'), 
                'bank', 
                ' ORDER BY '.$sortby.' '.$sort.' LIMIT '.$index.', '.$limit,
                array());
        }
        return array('countall'=>$len_bank, 'list'=>$bank_list);
    }

    public function InsertBank($name, $code) {
        $fields = array('name', 'code');
        $values = array($name, $code);
        $rows = $this->db->insert('bank', $fields, $values);
        return $rows;
    }

    public function UpdateBank($id, $name, $code) {
        $fields = array('name', 'code');
        $values = array($name, $code);
        $rows = $this->db->update('bank', $fields, $values, 'id = '.$id);
        return $rows;
    }

    public function DeleteBank($id) {
        $rows = $this->db->delete('bank', ' id = ?', array($id));
        return $rows;
    }

    public function len($where = '', $value = array()){ // count rows of admin table
        return $this->db->recordCount('bank', $where, $value);
    }
}