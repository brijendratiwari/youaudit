<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images extends CI_Controller {
	
	public function view($intId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
		
		$this->load->model('photos_model');
                //$this->load->library('image_lib');
                $objImage = $this->photos_model->getOne($intId);
               
		$this->output->set_header("Content-Type: ".$objImage->content_type);
                $this->load->file('./uploads/'.$objImage->file_name);
	}
	
	public function viewAvatar($intId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
		
                   
                
		$this->load->model('photos_model');
                
                $objImage = $this->photos_model->getOne($intId);
                
                $arrConfig['image_library']    = 'gd2';
                $arrConfig['source_image']     = './uploads/'.$objImage->file_name;
                $arrConfig['create_thumb']     = true;
                $arrConfig['maintain_ratio']   = true;
                $arrConfig['dynamic_output']   = true;
                $arrConfig['width']            = 35;
                $arrConfig['height']           = 35;
                
                $this->load->library('image_lib', $arrConfig);
                
                $this->image_lib->resize();
	}
        
        public function viewNewAvatar($intId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
		
                   
                
		$this->load->model('photos_model');
                
                $objImage = $this->photos_model->getOne($intId);
                
                $arrConfig['image_library']    = 'gd2';
                $arrConfig['source_image']     = './uploads/'.$objImage->file_name;
                $arrConfig['create_thumb']     = true;
                $arrConfig['maintain_ratio']   = true;
                $arrConfig['dynamic_output']   = true;
                $arrConfig['width']            = 25;
                $arrConfig['height']           = 25;
                
                $this->load->library('image_lib', $arrConfig);
                
                $this->image_lib->resize();
	}
        
        public function viewList($intId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
		
                   
                
		$this->load->model('photos_model');
                
                $objImage = $this->photos_model->getOne($intId);
                
                $arrConfig['image_library']    = 'gd2';
                $arrConfig['source_image']     = './uploads/'.$objImage->file_name;
                $arrConfig['create_thumb']     = true;
                $arrConfig['maintain_ratio']   = true;
                $arrConfig['dynamic_output']   = true;
                $arrConfig['width']            = 50;
                $arrConfig['height']           = 50;
                
                $this->load->library('image_lib', $arrConfig);
                
                $this->image_lib->resize();
	}
        public function viewHero($intId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
		
                   
                
		$this->load->model('photos_model');
                
                $objImage = $this->photos_model->getOne($intId);
                
                $arrConfig['image_library']    = 'gd2';
                $arrConfig['source_image']     = './uploads/'.$objImage->file_name;
                $arrConfig['create_thumb']     = true;
                $arrConfig['maintain_ratio']   = true;
                $arrConfig['dynamic_output']   = true;
                $arrConfig['width']            = 150;
                $arrConfig['height']           = 150;
                
                $this->load->library('image_lib', $arrConfig);
                
                $this->image_lib->resize();
	}
        
        public function viewDefaultImage($fileImage)
	{
            
            $intWidth = 113;
            $intHeight = 150;
            $ext = '.png';
            $file = $fileImage;
            $arrUriSegments = $this->uri->segment_array();
         
                //var_dump($file);
                if((isset($arrUriSegments[4])) && ($arrUriSegments[4] == 'icons')) {
                   
                    $intWidth = $arrUriSegments[7];
                    $intHeight = $arrUriSegments[8];
                
                
                //print $intWidth . " x " . $intHeight;
                //print " Ext is " . $ext;
                
                $file = $arrUriSegments[6];
                }
            
                
            if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
                
                $arrConfig['image_library']    = 'gd2';
                $arrConfig['source_image']     = '../img/icons/categories/'. $file;

                $arrConfig['create_thumb']     = true;
                $arrConfig['maintain_ratio']   = true;
                $arrConfig['dynamic_output']   = true;
                $arrConfig['width']            = $intWidth;
                $arrConfig['height']           = $intHeight;
                
                $this->load->library('image_lib', $arrConfig);
                
                $this->image_lib->resize();
	}
        
        
         public function viewAsset($intId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			show_error('Unauthorised Access');
		}
		
                   
                
		$this->load->model('photos_model');
                
                $objImage = $this->photos_model->getOne($intId);
                
                $arrConfig['image_library']    = 'gd2';
                $arrConfig['source_image']     = './uploads/'.$objImage->file_name;
                $arrConfig['create_thumb']     = true;
                $arrConfig['maintain_ratio']   = true;
                $arrConfig['dynamic_output']   = true;
                $arrConfig['width']            = 300;
                $arrConfig['height']           = 300;
                
                $this->load->library('image_lib', $arrConfig);
                
                $this->image_lib->resize();
	}
}