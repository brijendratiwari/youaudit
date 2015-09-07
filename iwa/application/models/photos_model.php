<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photos_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
         
    public function getOne($intId)
    {
        $this->db->from('photos');
        $this->db->where('id', $intId);
        $this->db->limit(1);
	$resQuery = $this->db->get();
        return $resQuery->row();
        
    }
    
    public function setOne($arrRawPhotoData, $strImageTitle, $strImageClass)
    {
        $arrPhotoData['path']           = "uploads/".$arrRawPhotoData['file_name'];
        $arrPhotoData['file_name']      = $arrRawPhotoData['file_name'];
        $arrPhotoData['image_width']    = $arrRawPhotoData['image_width'];
        $arrPhotoData['image_height']   = $arrRawPhotoData['image_height'];
        $arrPhotoData['content_type']   = $arrRawPhotoData['file_type'];
        $arrPhotoData['image_class']    = $strImageClass;
        $arrPhotoData['title']          = $strImageTitle;
        $this->db->insert('photos', $arrPhotoData);
        return $this->db->insert_id();
    }
    
}
?>