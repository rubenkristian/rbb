<?php
namespace App\Model;
use System\Model as Model;

class File extends Model{
    public function getDocumentsList() {
        $query = 'SELECT documents.name, documents.filename, documents_type.type_extension FROM documents INNER JOIN documents_type ON documents.type = documents_type.id ORDER BY documents.id ASC LIMIT 4';
        
        $documents = $this->db->rawQuery($query, array());
        
        return $documents;
    }
    
    public function getDocuments() {
        $query = 'SELECT documents.name, documents.filename, documents_type.type_extension FROM documents INNER JOIN documents_type ON documents.type = documents_type.id ORDER BY documents.id ASC LIMIT 4';
        
        $documents = $this->db->rawQuery($query, array());
        
        if($documents) {
            return array('status'=> true, 'documents'=> $documents);
        } else {
            return array('status'=> false, 'msg'=> 'Tidak ada yang ditampilkan.');
        }
    }
    
    public function getPromotion() {
        $images = $this->db->selectColumns(
            array(
                'id', 
                'name', 
                'context', 
                'dateupdate'), 
            'home_images', 
            'status = ? LIMIT 4', 
            array(1));
        
        if($images) {
            return array('status'=> true, 'images'=> $images);
        } else {
            return array('status'=> false, 'msg'=> 'Tidak ada yang ditampilkan.');
        }
    }
}