<?php
/**
 * Coconut ImagesController
 * Functions for gallery and upload images.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class ImagesController extends DooController{
	
	private $admin_view = 'admin/gallery/';
	
	
	/* Related functions of administrator */

    public function show_all_images(){
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		$page = ((intval($this->params['page']) - 1) * 9);
		
		Doo::loadModel('Image');
		$image = new Image;
		
		$data['images'] = $image->find(array('limit' => $page.', 9'));
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		$data['pagination'] = paginate($image->count(), $this->params['page'], 9);
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['page'] = $this->params['page'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Galería de Imágenes";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'listImages.html';
		$this->renderc('twig', $data);
    }

	public function upload_image(){
		include('protected/includes/imagesFunctions.php');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		Doo::loadModel('Image');

		$i = 0;

		foreach ($_FILES['file'] ['tmp_name'] as $file){

			if(isset($file) && (($_FILES['file']['type'][$i] == "image/jpeg") || ($_FILES['file']['type'][$i] == "image/png")) && ($_FILES['file']['size'][$i] < 20971520)){
				
				$image = new Image;
				
				try{
					$image_id = $image->insert();
		 			move_uploaded_file($file, "global/img/gallery/".$image_id);
					create_thumbnail($image_id);
				}catch(Exception $e){
					echo "error";
				}
				
			}else{
				echo "error";
			}
			
			$i ++;
		}
	}
	
	public function view_image(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!is_numeric($this->params['id']) || !is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		Doo::loadModel('Image');
		
		if(count($_POST) > 0){
			$image = new Image;
			$image->id = $this->params['id'];
			$image->title = $_POST['title'];
			$image->text = $_POST['text'];
			$image->update();
		}
		
		$image = new Image;
		$image->id = $this->params['id'];
		
		$data['image'] = $image->getOne();
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['page'] = $this->params['page'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Galería de Imágenes";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'showImage.html';
		$this->renderc('twig', $data);
	}
	
	public function remove_image(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['id']) || !is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!is_numeric($this->params['id']))
			header("Location:".$_SERVER['HTTP_REFERER']);
		
		Doo::loadModel('Image');
		$image = new Image;
		$image->id = $this->params['id'];
		try{
			$image->delete();
		}catch(Exception $e){
			echo ""; //pass
		}
		
		return $auth_url."/galeria/".$this->params['page'];
	}

    public function image_uploader(){
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		$page = ((intval($this->params['page']) - 1) * 9);
		
		Doo::loadModel('Image');
		$image = new Image;
		
		$data['images'] = $image->find(array('limit' => $page.', 9'));
		
		$data['pagination'] = paginate($image->count(), $this->params['page'], 9);
		
		$data['type'] = $this->params['type'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
    	$data['title'] = "Administración - Cargador de Imágenes";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'imageUploader.html';
		$this->renderc('twig', $data);
    }

	public function edit_slider_images(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		include('protected/includes/imagesFunctions.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		if(count($_POST) > 0)
			if(isset($_POST['gallery']))
				save_slider_images($_POST['gallery']);
	
		Doo::loadModel('Image');
		$image = new Image;
		$image->slider = 1;
		$data['images'] = $image->find();
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['page'] = $this->params['page'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Carrusel Imágenes";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'editSlider.html';
		$this->renderc('twig', $data);
	}

}
?>
