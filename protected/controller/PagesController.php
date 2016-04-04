<?php
/**
 * Coconut PagesController
 * Functions for pages.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class PagesController extends DooController{

	private $admin_view = 'admin/pages/';
	
	private $public_view = 'public/pages/';


	/* Related functions of administrator */

    public function show_all_pages(){
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		$page = ((intval($this->params['page']) - 1) * 20);
		
		Doo::loadModel('Page');
		$one_page = new Page;
		
		$data['pages'] = $one_page->get_list_contents($page);
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		$data['pagination'] = paginate($one_page->count(), $this->params['page'], 20);
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['page'] = $this->params['page'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Páginas";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'listPages.html';
		$this->renderc('twig', $data);
    }

    public function create_one_page(){
    	include('protected/config/settings.php');
		include('protected/includes/pagesFunctions.php');
		include('protected/includes/textFunctions.php');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}

		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}

		if(count($_POST) > 0){
			
			Doo::loadModel('Page');
			$page = new Page;
			
			if(isset($_POST['featuredImage']))
				$featured_image = 1;
			else
				$featured_image = 0;	
			
			$page->featured_image = $featured_image;
			
			if(isset($_POST['featuredImageSelected']))
				$page->images_id = $_POST['featuredImageSelected'];
			
			if(isset($_POST['home'])){
				$home_pages = new Page;
				$home_pages->home = 1;
				if($home_pages->count() < $pages_home)
					$page->home = 1;
				else
					$page->home = 0;
			}
			
			$page_id = $page->insert();

			Doo::loadModel('Page_Content');
			$flag = true;
			foreach($this->_application->idioms as $idiom){
				if($_POST['title'.$idiom->id] != ""){
					$page_content = new Page_Content;
					$page_content->title = $_POST['title'.$idiom->id];
					$page_content->text = str_replace("../", $project_url,str_replace("../../", $project_url,str_replace("../../../", $project_url,str_replace("../../../../", $project_url,str_replace("../../../../../", $project_url, $_POST['text'.$idiom->id])))));
					$page_content->slug = slug_generate($page_content->title);
					$page_content->description = $_POST['description'.$idiom->id];
					$page_content->keywords = $_POST['keywords'.$idiom->id];
					$page_content->idioms_id = $idiom->id;
					$page_content->pages_id = $page_id;
				
					if($flag){
						$slug = $page_content->slug;
						$flag = false;	
					}
				
					if(!insert_page($page_content)){
						$data['onePage'] = $_POST;
						$data['error'] = "Error al añadir la entrada en la base de datos";
					}
				}
			}

			if(isset($_POST['gallery']))
				save_gallery_images($_POST['gallery'], $page_id);
			
			if(!isset($data['error'])){
				return $auth_url.'/pagina/editar/'.$slug.'/0/';
			}

		}
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Crear Página";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'editPage.html';
		$this->renderc('twig', $data);
    }
	
	public function edit_one_page(){
		include('protected/config/settings.php');
		include('protected/includes/pagesFunctions.php');
		include('protected/includes/textFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		Doo::loadModel('Page');
		Doo::loadModel('Page_Content');
		Doo::loadModel('Page_Image');
		
		$page = new Page_Content;
		$page->slug = $this->params['slug'];
		$page = Doo::db()->find($page)[0];
		$page_id = $page->pages_id;
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		for($i = 1; $i <= count($this->_application->idioms); $i++){
			
			if(isset($_POST['title'.$i])){
				$page = new Page_Content;
				$page->pages_id = $page_id;
				$page->idioms_id = $i;
				$page_saved = $page->getOne();
				
				$page->title = $_POST['title'.$i];
				$page->text = str_replace("../", $project_url,str_replace("../../", $project_url,str_replace("../../../", $project_url,str_replace("../../../../", $project_url,str_replace("../../../../../", $project_url, $_POST['text'.$i])))));
				$page->description = $_POST['description'.$i];
				$page->keywords = $_POST['keywords'.$i];
				
				if($page_saved != false){
					$page->id = $page_saved->id;
					$page->update();
				}else{
					if($page->title != ""){
						$page->slug = slug_generate($page->title);
						if(!insert_page($page)){
							$data['onePage'] = $_POST;
							$data['error'] = "Error al añadir la entrada que no existía en la base de datos";
						}
					}
				}
			}
		}
		
		if(isset($_POST['title1']) || isset($_POST['title2']) || isset($_POST['title3'])){
			
			$page = new Page;
			$page->id = $page_id;
			$page->edit = date("Y-m-d h:m:s");
			
			if(isset($_POST['home'])){
				$home_pages = new Page;
				$home_pages->home = 1;
				
				$home_page = $home_pages;
				$home_page->id = $page_id;
				
				if(($home_pages->count() < $pages_home) || ($home_page->count() > 0))
					$page->home = 1;
				else{
					$page->home = 0;
					$data["error"] = "Se está superando el límite de páginas en el inicio. Desactiva alguna para activar esta.";
				}
			}else{
				$page->home = 0;
			}
			
			if(isset($_POST['featuredImage']))
				$featured_image = 1;
			else
				$featured_image = 0;	
			
			$page->featured_image = $featured_image;
			
			if(isset($_POST['featuredImageSelected']))
				$page->images_id = $_POST['featuredImageSelected'];
			else 
				$page->images_id = 0;
			
			$page->update();
			
			if(isset($_POST['gallery']))
				save_gallery_images($_POST['gallery'], $page_id);
			else
				remove_gallery_images($page_id);
		}
		
		if(strpos($_SERVER["HTTP_REFERER"], 'crear') > 0){
			$data['new'] = true;
		}
		
		$page = new Page;
		$data['onePage'] = $page->get_page_all_contents($this->params['slug']);
		
		$pages_images = new Page_Image;
		$pages_images->pages_id = $page_id;
		$data['images'] = $pages_images->find();
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['page'] = $this->params['page'];
		$data['edit'] = true;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Administración - Editar ".$data['onePage'][0]->title;
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'editPage.html';
		$this->renderc('twig', $data);
	}
	
	public function remove_one_page(){
		include('protected/config/settings.php');
		include('protected/includes/pagesFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		Doo::loadModel('Page_Content');
		
		$page = new Page_Content;
        $page->slug = $this->params['slug'];
        $page = Doo::db()->find($page)[0];
		
		$page_id = $page->pages_id;
		
		remove_gallery_images($page_id);
		
		$page = new Page_Content;
        $page->pages_id = $page_id;
        Doo::db()->delete($page);
		
		Doo::loadModel('Page');
		
		$page = new Page;
		$page->id = $page_id;
        Doo::db()->delete($page);
		
		Doo::loadModel('Link_Page');
		
		$link_page = new Link_Page;
		$link_page->pages_id = $page_id;
		$one_link = $link_page->getOne();
        Doo::db()->delete($link_page);
		
		
		if($one_link != false){
			Doo::loadModel('Link');
		
			$link = new Link;
			$link->id = $one_link->links_id;
		
			Doo::db()->delete($link);
		}
		
		Doo::loadModel('List_Page');
		
		$list_page = new List_Page;
		$list_page->pages_id = $page_id;
        Doo::db()->delete($list_page);
		
		return $auth_url."/pagina/listar/".$this->params['page'];
	}
	
	
	/* Functions relating to the public part */
	
	public function show_one_page(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		Doo::loadModel('Link');
		
		$this->_application = Doo::session("web");
		
		$this->_application->last_url = $_SERVER['REQUEST_URI'];
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms) || !isset($this->_application->header_links) || !isset($this->_application->sidebar_links) || !isset($this->_application->selected_idiom)){
			$session = start_public_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
			$this->_application->header_links = $session[4];
			$this->_application->sidebar_links = $session[5];
			$this->_application->subscribe  = $session[6];
			$this->_application->selected_idiom  = 1;
		}
		
		if(isset($this->params['slug2'])){ //Get to page through a list
		
			if(!is_numeric($this->params['page']))
				header('Location: ' . $project_url . 'error');
		
			$slug = $this->params['slug2'];
			$data['list_slug'] = $this->params['slug'];
			$data['list_page'] = $this->params['page'];
			
			Doo::loadModel('List_Content');
			$list = new List_Content;
			$list->slug = $this->params['slug'];
			$list->idioms_id = $this->_application->selected_idiom;
			$one_list = $list->getOne();
			$data['list_title'] = $one_list->title;
			
		}else
			$slug = $this->params['slug'];
		
		Doo::loadModel('Page_Content');
		$page_content = new Page_Content;
		$page_content->slug = $slug;
		$data['onePage'] = $page_content->getOne();
		
		if($data['onePage'] == false)
			header('Location: ' . $project_url . 'error');
		
		Doo::loadModel('Page');
		$page = new Page;
		$page->id = $data['onePage']->pages_id;
		$data['page'] = $page->getOne(array('select' => 'featured_image, images_id'));
		
		Doo::loadModel('Page_Image');
		$page_images = new Page_Image;
		$page_images->pages_id = $data['onePage']->pages_id;
		$data['images'] = $page_images->find();
		
		$data['header_links'] = $this->_application->header_links;
		$data['sidebar_links'] = $this->_application->sidebar_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['title'] = $data['onePage']->title . " - " . $this->_application->company->name;
		$data['description'] = $data['onePage']->description;
		$data['keywords'] = $this->_application->company->keywords;
		
		if(isset($this->_application->company->keywords) && isset($data['onePage']->keywords))
			$data['keywords'] .= ", ";
		
		$data['keywords'] .= $data['onePage']->keywords;
    	$data['view'] = $this->public_view.'onePage.html';

		$this->renderc('twig', $data);
	}
	
}
?>
