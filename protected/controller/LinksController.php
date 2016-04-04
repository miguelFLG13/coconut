<?php
/**
 * Coconut LinksController
 * Functions for links.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class LinksController extends DooController{

	private $view = 'admin/links/';

	public function edit_one_link(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['type']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth || ($this->params['type'] < 0) || ($this->params['type'] > 1)){
			return $auth_url;
		}
		
		if((isset($this->_application->header_links) || isset($this->_application->sidebar_links)) && isset($_POST['page']) && isset($_POST['name'])){ //If the public session started, change the links
			$session = start_public_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
			$this->_application->header_links = $session[4];
			$this->_application->sidebar_links = $session[5];
		}elseif(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists)){	
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		Doo::loadModel('Link');
		
		Doo::loadModel('Link_Page');
		
		Doo::loadModel('Link_Content');
		
		Doo::loadModel('List_Content');
		
		Doo::loadModel('Link_List');
		
		Doo::loadModel('Link_Blog');
		
		$error = false;

		if(isset($_POST['page']) || isset($_POST['name']) || isset($_POST['blog']) || isset($_POST['list'])){ //Save new link
			
			$all_links = obtain_links($this->params['type']); //To check another link with the same name
			
			if(count($all_links) > 0){
				$order = array(); //Prepare to know the next link order
				foreach($all_links as $one_link)
					array_push($order, $one_link->link_order);
			
				$last_link = max($order) + 1; //The next link order
			}else{
				$last_link = 1;
			}
			
			$link = new Link;
			$link->is_header = $this->params['type'];
			$link->link_order = $last_link;
			
			if($_POST['page'] != ''){
				
				Doo::loadModel('Page_Content');
				
				$page = New Page_Content;
				$page->pages_id = $_POST['page'];
				$page->idioms_id = 1;
				$one_page = $page->getOne();
				
				//Check another link with the same name
				foreach($all_links as $one_link){
					if($one_link->name == $one_page->title){
						$error = true;
						break;
					}
				}
				
				if(!$error){
					//Save the link
					$link->id = Doo::db()->insert($link);
					
					$link_page = new Link_Page;
					$link_page->links_id = $link->id;
					$link_page->pages_id = $_POST['page'];
					$link_page = $link_page->insert();
				}
			}else if($_POST['list'] != ''){
				
				$list = New List_Content;
				$list->lists_id = $_POST['list'];
				$list->idioms_id = 1;
				$one_list = $list->getOne();
				
				//Check another link with the same name
				foreach($all_links as $one_link){
					if($one_link->name == $one_list->title){
						$error = true;
						break;
					}
				}
				
				if(!$error){
					//Save the link
					$link->id = Doo::db()->insert($link);
					
					$link_list = new Link_List;
					$link_list->links_id = $link->id;
					$link_list->lists_id = $_POST['list'];
					$link_list = $link_list->insert();
				}
			}else if($_POST['blog'] != ''){
				Doo::loadModel('Blog');
				
				$blog = New Blog;
				$blog->id = $_POST['blog'];
				$blog->idioms_id = 1;
				$one_blog = $blog->getOne();
				
				//Check another link with the same name
				foreach($all_links as $one_link){
					if($one_link->name == $one_blog->title){
						$error = true;
						break;
					}
				}
				
				if(!$error){
					//Save the link
					$link->id = Doo::db()->insert($link);
					
					$link_blog = new Link_Blog;
					$link_blog->links_id = $link->id;
					$link_blog->blogs_id = $_POST['blog'];
					$link_blog = $link_blog->insert();
				}
			}else{
				
				//Check another link with the same name
				foreach($all_links as $one_link){
					if($one_link->name == $_POST['name1']){
						$error = true;
						break;
					}
				}
				
				if(!$error){
					//Save the link
					$link->id = Doo::db()->insert($link);
					
					foreach($this->_application->idioms as $idiom){
						$link_content = new Link_Content;
						$link_content->name = $_POST['name'.$idiom->id];
						$link_content->url = $_POST['url'.$idiom->id];
						$link_content->idioms_id = $idiom->id;
						$link_content->links_id = $link->id;
						$link_content = $link_content->insert();
					}
				}
			}
			
			if($error)
				$data['error'] = "Ya existe un enlace con ese nombre";
		}
		
		if(isset($_POST['link'])){ //Change the order for the links or convert a link into a son or into a parent
			$i = 0;
			foreach($_POST['link'] as $link_id){
				
				//Check if parent link is a son link
				if($_POST['sublink'][$i] != 0){
					$parent_link = new Link;
					$parent_link->id = $_POST['sublink'][$i];
					$one_link = $parent_link->getOne();
					
					if($one_link->parent_id != 0)
						$data['error'] = "Solo puede haber un nivel en la jerarquía de enlaces";
				}
				
				if((count($_POST['linkOrder']))!=(count(array_unique($_POST['linkOrder']))))
					$data['error'] = "No puede haber dos enlaces con el mismo número de orden";
				
				if(!isset($data['error'])){
					//Save the changes
					$link = new Link;
					$link->id = $link_id;
					$link->link_order = $_POST['linkOrder'][$i];
					$link->parent_id = $_POST['sublink'][$i];
					$link->update();
					$i++;
				}	
			}
		}
		
		if(count($_POST) > 0){
			$session = start_public_session();
			$this->_application->header_links = $session[4];
			$this->_application->sidebar_links = $session[5];
		}
		
		$link = new Link;
		$data['links'] = obtain_links($this->params['type']);
		$data['type'] = $this->params['type'];

		$one_page = new Page;
		
		$data['pages'] = $one_page->get_list_contents(-1);
		
		$lists = New List_Content;
		$lists->idioms_id = 1;
		$data['lists_link'] = $lists->find();
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		
		if($this->params['type'])
			$data['title'] = "Administración - Enlaces Cabecera";
		else
			$data['title'] = "Administración - Enlaces Barra";
		
		$data['description'] = "";
    	$data['view'] = $this->view.'editLink.html';
		$this->renderc('twig', $data);
	}

	public function remove_one_link(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['type']) || !is_numeric($this->params['id']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		//Resolve the son links removing the father
		Doo::loadModel('Link');
		$link = new Link;
		$link->parent_id = $this->params['id'];
		$son_links = $link->find();
		
		foreach ($son_links as $son_link) {
			$link = new Link;
			$link->id = $son_link->id;
			$link->parent_id = 0;
			$link->update();
		}
		
		//Delete data of the link
		Doo::loadModel('Link_Page');
		$link_page = new Link_Page;
		$link_page->links_id = $this->params['id'];
		
		$link_page->delete();
		
		Doo::loadModel('Link_List');
		$link_list = new Link_List;
		$link_list->links_id = $this->params['id'];
		
		$link_list->delete();
		
		Doo::loadModel('Link_Blog');
		$link_blog = new Link_Blog;
		$link_blog->links_id = $this->params['id'];
		
		$link_blog->delete();
		
		Doo::loadModel('Link_Content');
		$link_content = new Link_Content;
		$link_content->links_id = $this->params['id'];
		
		$link_content->delete();
		
		
		$link = new Link;
		$link->id = $this->params['id'];
		
		$link->delete();
		
		$session = start_public_session();
		$this->_application->header_links = $session[4];
		$this->_application->sidebar_links = $session[5];
		
		return $auth_url."/enlaces/listar/".$this->params['type'];
	}

}
?>
