<?php
/**
 * Coconut MainController
 * Funtion for home page.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class MainController extends DooController{

	/* Functions relating to the public part */
	
    public function index(){
    	Doo::loadModel('Link');
		
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		$this->_application = Doo::session("web");
		
		$this->_application->last_url = $_SERVER['REQUEST_URI'];
		
		if(!isset($this->_application->selected_idiom)){
			$this->_application->selected_idiom = 1;
		}
		
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
		
		Doo::loadModel('Post');
		$posts = new Post;
		
		if($blog_home != 0){
			Doo::loadModel('Blog');
			$blog = new Blog;
			$blog->idblog = $blog_home;
			$blog->idioms_id = $this->_application->selected_idiom;
			$one_blog = $blog->getOne();
			
			$data['blog'] = $one_blog;
			
			$data['posts'] = $posts->get_list_contents($blog_home, 0, $this->_application->selected_idiom, $posts_home);
		}

		Doo::loadModel('Page');
		$pages = new Page;
		$data['pages'] = $pages->get_list_contents(0, $this->_application->selected_idiom, $pages_home, 1);
		
		$data['pages_name'] = $this->_application->company->slogan;
		
		Doo::loadModel('Image');
		$images = new Image;
		$images->slider = 1;
		$data['slider_images'] = $images->find();
		
		$data['header_links'] = $this->_application->header_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['title'] = $this->_application->company->name;
		$data['description'] = $this->_application->company->description;
		$data['keywords'] = $this->_application->company->keywords;
    	$data['view'] = 'public/index.html';

		$this->renderc('twig', $data);
    }

	public function change_idiom(){
		
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['idiom']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		$last_idiom = $this->_application->selected_idiom;
		$this->_application->selected_idiom  = $this->params['idiom'];
		
		$url_parts = explode('/', $this->_application->last_url);
		
		if($url_parts[count($url_parts) - 1] == '')
			$lenght_parts = count($url_parts) - 1;
		else
			$lenght_parts = count($url_parts);
		
		if($this->_application->last_url[count($this->_application->last_url) - 1] != '/')
			$this->_application->last_url .= '/';
		
		if(($this->_application->last_url == '/'.$local_url) || ($url_parts[$lenght_parts - 1] == "contacto")){
			$this->_application->header_links = obtain_links(1, $this->_application->selected_idiom);
			$this->_application->sidebar_links = obtain_links(0, $this->_application->selected_idiom);
			$session = start_admin_session($this->_application->selected_idiom);
			$this->_application->company = $session[0];
		
			header('Location: ' . $this->_application->last_url);
		}	
		
		if($url_parts[$lenght_parts - 1] == "buscar"){
			$url = $this->_application->last_url.'/'. $this->_application->last_search .'/1/1';
		}elseif(($url_parts[$lenght_parts - 2] == "pagina") && isset($url_parts[$lenght_parts - 5])){
			
			if($url_parts[$lenght_parts - 5] == "lista"){
				
				Doo::loadModel('Page_Content');
				$old_page = new Page_Content;
				$old_page->slug = $url_parts[$lenght_parts -1];
				$one_old_page = $old_page->getOne();
			
				$page = new Page_Content;
				$page->idioms_id = $this->_application->selected_idiom;
				$page->pages_id = $one_old_page->pages_id;
				$one_page = $page->getOne();
			
				if($one_page == false){
					$this->_application->selected_idiom  = $last_idiom;
					header('Location: ' . $project_url . 'error');
				}
			
				Doo::loadModel('List_Content');
				$old_list = new List_Content;
				$old_list->slug = $url_parts[$lenght_parts - 4];
				$one_old_list = $old_list->getOne();
			
				$list = new List_Content;
				$list->idioms_id = $this->_application->selected_idiom;
				$list->lists_id = $one_old_list->lists_id;
				$one_list = $list->getOne();
			
				$url = '';
				for($i = 0; $i < $lenght_parts - 5; $i++)
					$url .= $url_parts[$i] . '/';
			
				$url .= 'lista/' . $one_list->slug . '/' . $url_parts[$lenght_parts - 3] . '/pagina/' . $one_page->slug;
			
			}
		
		}elseif($url_parts[$lenght_parts - 2] == "pagina"){ //It is a page, search the correct slug
			
			Doo::loadModel('Page_Content');
			$old_page = new Page_Content;
			$old_page->slug = $url_parts[$lenght_parts -1];
			$one_old_page = $old_page->getOne();
			
			$page = new Page_Content;
			$page->idioms_id = $this->_application->selected_idiom;
			$page->pages_id = $one_old_page->pages_id;
			$one_page = $page->getOne();
			
			if($one_page == false){
				$this->_application->selected_idiom  = $last_idiom;
				header('Location: ' . $project_url . 'error');
			}
			
			$url = '';
			for($i = 0; $i < $lenght_parts - 1; $i++)
				$url .= $url_parts[$i] . '/';
			
			$url .= $one_page->slug;
		}elseif($url_parts[$lenght_parts - 3] == "lista"){ //It is a list, search the correct slug
		
			Doo::loadModel('List_Content');
			$old_list = new List_Content;
			$old_list->slug = $url_parts[$lenght_parts -2];
			$one_old_list = $old_list->getOne();
			
			$list = new List_Content;
			$list->idioms_id = $this->_application->selected_idiom;
			$list->lists_id = $one_old_list->lists_id;
			$one_list = $list->getOne();
			
			$url = '';
			for($i = 0; $i < $lenght_parts - 3; $i++)
				$url .= $url_parts[$i] . '/';
			
			$url .= 'lista/' . $one_list->slug . '/' . $url_parts[$lenght_parts - 1];
		
		}elseif(($url_parts[$lenght_parts - 3] == "blog") && ($url_parts[$lenght_parts - 1] != "post")){ //It is a blog, search the correct slug
		
			Doo::loadModel('Blog');
			$old_blog = new Blog;
			$old_blog->slug = $url_parts[$lenght_parts - 2];
			$one_old_blog = $old_blog->getOne();
			
			$blog = new Blog;
			$blog->idioms_id = $this->_application->selected_idiom;
			$blog->idblog = $one_old_blog->idblog;
			$one_blog = $blog->getOne();
			
			$url = '';
			for($i = 0; $i < $lenght_parts - 3; $i++)
				$url .= $url_parts[$i] . '/';
			
			$url .= 'blog/' . $one_blog->slug . '/' . $url_parts[$lenght_parts - 1];
		}elseif($url_parts[$lenght_parts - 5] == "buscar"){
			$url = $this->_application->last_url;
		}elseif(($url_parts[$lenght_parts - 5] == "blog") && ($url_parts[$lenght_parts - 3] == "post")){
			
			Doo::loadModel('Blog');
			$old_blog = new Blog;
			$old_blog->slug = $url_parts[$lenght_parts - 4];
			$one_old_blog = $old_blog->getOne();
			
			$blog = new Blog;
			$blog->idioms_id = $this->_application->selected_idiom;
			$blog->idblog = $one_old_blog->idblog;
			$one_blog = $blog->getOne();
			
			Doo::loadModel('Post');
			$old_post = new Post;
			$old_post->blogs_id = $blog->idblog;
			$one_old_post = $old_post->relate('Post_Content', array('where' => 'slug = "'.$url_parts[$lenght_parts - 2].'"'))[0];
			
			Doo::loadModel('Post_Content');
			$post = new Post_Content;
			$post->idioms_id = $this->_application->selected_idiom;
			$post->posts_id = $one_old_post->id;
			$one_post = $post->getOne();
			
			if($one_post == false){
				
				$this->_application->selected_idiom  = $last_idiom;
				header('Location: ' . $project_url . 'error');
			}
			
			$url = '';
			for($i = 0; $i < $lenght_parts - 5; $i++)
				$url .= $url_parts[$i] . '/';
			
			$url .= 'blog/' . $one_blog->slug . '/post/' . $one_post->slug . '/' . $url_parts[$lenght_parts - 1];
			
		}else
			$url = $this->_application->last_url;
		
		$this->_application->header_links = obtain_links(1, $this->_application->selected_idiom);
		
		$this->_application->sidebar_links = obtain_links(0, $this->_application->selected_idiom);
		
		$session = start_admin_session($this->_application->selected_idiom);
		$this->_application->company = $session[0];

		if(isset($url))
			header('Location: ' . $url);
		else
			header('Location: ' . $project_url . 'error');
	}
	
	public function search(){
		Doo::loadModel('Link');
		
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		$this->_application = Doo::session("web");
		
		$this->_application->last_url = $_SERVER['REQUEST_URI'];
		
		if(!isset($this->_application->selected_idiom)){
			$this->_application->selected_idiom = 1;
		}
		
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
		
		if(isset($_POST['searcher'])){
			$search = $_POST['searcher'];
			$this->_application->last_search = $_POST['searcher'];
		}elseif(isset($this->params['search']))
			$search = $this->params['search'];
		else 
			$search = '';
		
		$data['search'] = $search;
		
		if(isset($_POST['page'])){
			$page1 = $_POST['page'] - 1;
			$page2 = $_POST['page'] - 1;
		}elseif(isset($this->params['page1'])){
			$page1 = $this->params['page1'] - 1;
			$page2 = $this->params['page2'] - 1;
		}else{
			$page1 = 0;
			$page2 = 0;
		}
		
		$num_of_searches = 9;
		
		Doo::loadModel('Page');
		$pages = new Page;
		$data['pages'] = $pages->search_page($search, $this->_application->selected_idiom, ($page1 * $num_of_searches), $num_of_searches);
		
		$data['pagination1'] = paginate($pages->count_search_page($search, $this->_application->selected_idiom), ($page1 + 1), $num_of_searches);
		$data['page1'] = $page1 + 1;
		
		Doo::loadModel('Post');
		$posts = new Post;
		$data['posts'] = $posts->search_post($search, $this->_application->selected_idiom, ($page2 * $num_of_searches), $num_of_searches);
		
		$data['pagination2'] = paginate($posts->count_search_post($search, $this->_application->selected_idiom), ($page2 + 1), $num_of_searches);
		$data['page2'] = $page2 + 1;
		
		$data['header_links'] = $this->_application->header_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['title'] = $search . " - " . $this->_application->company->name;
		$data['description'] = $this->_application->company->description;
		$data['keywords'] = $this->_application->company->keywords;
    	$data['view'] = 'public/search.html';
		$this->renderc('twig', $data);
	}

	function subscribe_email(){
	
		include('protected/config/settings.php');
	
		$this->_application = Doo::session("web");
	
		if(isset($_POST['email']))
			if(!preg_match("/^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})$/", $_POST['email'])){
				$this->_application->subscribe = false;
			}else{

				try{
					Doo::loadModel('Email');
					$email = new Email;
					$email->address = $_POST['email'];
					$email->insert();
					setcookie("subscribe","true");
					$this->_application->subscribe = true;
				}catch(Exception $e){
					$this->_application->subscribe = false;
				}
			
			}

		header('Location: ' . $project_url);
	}

	public function contact_page(){
		Doo::loadModel('Link');
		
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		$this->_application = Doo::session("web");
		
		$this->_application->last_url = $_SERVER['REQUEST_URI'];
		
		if(!isset($this->_application->selected_idiom)){
			$this->_application->selected_idiom = 1;
		}
		
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

		if(isset($_POST['subject']) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['comments'])){

			try{
				if(mail($this->_application->company->email, $_POST['subject'], "Enviado por : " . $_POST['email'] . "\n\n". $_POST['comments'], "FROM: ".$_POST['email']))
					$data['send'] = true;
				else
					$data['send'] = false;

			}catch(Exception $e){
				$data['send'] = false;
			}
			
		}
		
		if(!isset($data['send']))
			$data['send'] = "None";
		
		$data['header_links'] = $this->_application->header_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['title'] = "Contacto - " . $this->_application->company->name;
		$data['description'] = $this->_application->company->description;
		$data['keywords'] = $this->_application->company->keywords;
    	$data['view'] = 'public/contact.html';

		$this->renderc('twig', $data);
	}

}
?>
