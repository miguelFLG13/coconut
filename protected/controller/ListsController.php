<?php
/**
 * Coconut ListController
 * Functions for lists.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class ListsController extends DooController{

	private $admin_view = 'admin/lists/';

	private $public_view = 'public/lists/';

	public function show_all_lists(){
		include('protected/config/settings.php');
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
		
		Doo::loadModel('List_Content');
		$lists = new List_Content;
		$lists->idioms_id = $this->_application->idioms[0]->id;
		$all_lists = $lists->find();
		
		$data['lists'] = $all_lists;
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['idioms'] = $this->_application->idioms;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Administración - Ver Listas";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'listLists.html';
		$this->renderc('twig', $data);
	}


	public function create_one_list(){
		include('protected/config/settings.php');
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
			include('protected/includes/textFunctions.php');
			
			Doo::loadModel('oneList');
			$list = new oneList;
			$list_id = $list->insert();
			
			$flag = true;
			
			Doo::loadModel('List_Content');
			
			foreach ($this->_application->idioms as $idiom){
				$list_content = new List_Content;
				$list_content->lists_id = $list_id;
				$list_content->title = $_POST['title'.$idiom->id];
				$list_content->slug = slug_generate($list_content->title);
				$list_content->description = $_POST['description'.$idiom->id];
				$list_content->keywords = $_POST['keywords'.$idiom->id];
				$list_content->idioms_id = $idiom->id;
				
				try{
					$list_content->insert();
				}catch (Exception $e){
    				try { //If the admin try add a content with duplicate title
    					$random = rand(1, 99);
						$list_content->title .=  ' '.$random;
    					$list_content->slug .= '-'.$random;
    					$list_content->insert();
					}catch (Exception $e2){
						$data['oneList'] = $_POST;
						$data['error'] = "Error al añadir la entrada en la base de datos";
					}
				}
				
				if($flag){ //Obtain slug for principal idiom to redirect
					$slug = $list_content->slug;
					$flag = false;
				}
			}
			
			//Update session var for lists
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->lists = $session[2];
			
			return $auth_url."/listado/editar/".$slug."/1/"; //Go to edit lists
		}
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Crear Lista";
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'createList.html';
		$this->renderc('twig', $data);
	}

	public function edit_one_list(){
		include('protected/config/settings.php');
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
		
		Doo::loadModel('List_Content');
		$list = new List_Content;
		$list->slug = $this->params['slug'];
		$list = $list->getOne();
		$data['list'] = $list;
		
		
		$list_content = new List_Content;
		$list_content->lists_id = $list->lists_id;
		$all_list_content = $list_content->find();
		$data['list_contents'] = $all_list_content;
		
		if(count($_POST) > 0){
			$i = 0;
			foreach ($this->_application->idioms as $idiom){
				$all_list_content[$i]->description = $_POST['description'.$idiom->id];
				$all_list_content[$i]->keywords = $_POST['keywords'.$idiom->id];
				$all_list_content[$i]->update();
				$i++;
			}
		}
		
		Doo::loadModel('List_Page');

		if(isset($_POST['pages'])){
			$selected_page = new List_Page;
        	$selected_page->lists_id = $list->lists_id;
        	$selected_page->delete();
				
			foreach($_POST['pages'] as $page){
				$selected_page = new List_Page;
        		$selected_page->lists_id = $list->lists_id;
				$selected_page->pages_id = $page;
				$selected_page->insert();
			}
		}
		
		$selected_page = new List_Page;
		$selected_page->lists_id = $list->lists_id;
		$selected_pages = $selected_page->find();
		
		Doo::loadModel('Page');
		$page = new Page;
		$pages = $page->get_list_contents(-1, 1);
		
		if($pages == null)
			$pages = array();

		$i = 0;
		foreach($pages as $page){
			$page->selected = false;
			foreach($selected_pages as $selected_page){
				if($page->pages_id == $selected_page->pages_id){
					$page->selected = true;
					break;
				}
			}
			$data['pages'][$i] = $page;
			$i++;
		}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists)){
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
		}
		
		if(isset($this->params['created']))
			$data['new'] = true;
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Administración - Editar Lista ".$list->title;
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'editList.html';
		$this->renderc('twig', $data);
	}

	public function remove_one_list(){
		include('protected/config/settings.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		Doo::loadModel('List_Content');
		
		$list = new List_Content;
        $list->slug = $this->params['slug'];
        $list = Doo::db()->find($list)[0];
		
		$list_id = $list->lists_id ;

		Doo::loadModel('List_Page');
		$list_page = new List_Page;
		$list_page->lists_id = $list_id;
		$list_page->delete();
		
		
		$list = new List_Content;
        $list->lists_id = $list_id;
        $list->delete();
		
		Doo::loadModel('oneList');
		
		$list = new oneList;
		$list->id = $list_id;
        $list->delete();
		
		return $auth_url."/listado/listar/";
	}
	
	public function show_one_list(){
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
		
		Doo::loadModel('List_Content');
		$list = new List_Content;
        $list->slug = $this->params['slug'];
		$list->idioms_id = $this->_application->selected_idiom;
		$one_list = $list->getOne();
		
		$data['list'] = $one_list;
		
		$num_page = ($this->params['page'] - 1) * 10;
		
		if(isset($one_list->lists_id))
			$data['list_pages'] = Doo::db()->query("SELECT * FROM lists_has_pages l, pages p, pages_content pc WHERE (l.lists_id = ". $one_list->lists_id .") AND (l.pages_id = pc.pages_id) AND (pc.idioms_id = " . $this->_application->selected_idiom . ") AND (pc.pages_id = p.id) LIMIT ".$num_page.", 9;")->fetchAll();
		else
			return $project_url . 'error';
		
		$data['page'] = $this->params['page'];
		
		$pagination_number = Doo::db()->query("SELECT count(*) FROM lists_has_pages l, pages p, pages_content pc WHERE (l.lists_id = ". $one_list->lists_id .") AND (l.pages_id = pc.pages_id) AND (pc.idioms_id = " . $this->_application->selected_idiom . ") AND (pc.pages_id = p.id);")->fetchAll()[0]["count(*)"];
		$data['pagination'] = paginate($pagination_number, $data['page']);
		$data['list_slug'] = $this->params['slug'];
		
		$data['header_links'] = $this->_application->header_links;
		$data['sidebar_links'] = $this->_application->sidebar_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['title'] = $one_list->title . " - " . $this->_application->company->name;
		$data['description'] = $one_list->description;
		$data['keywords'] = $this->_application->company->keywords;
		
		if(isset($this->_application->company->keywords) && isset($one_list->keywords))
			$data['keywords'] .= ", ";
		
		$data['keywords'] .= $one_list->keywords;
    	$data['view'] = $this->public_view.'listPages.html';

		$this->renderc('twig', $data);
	}
}
?>
