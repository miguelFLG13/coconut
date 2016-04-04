<?php
/**
 * Coconut PostController
 * Functions for blogs and posts.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class PostController extends DooController{
	
	private $admin_view = 'admin/posts/';
	
	private $public_view = 'public/posts/';
	
	
	/* Related functions of administrator */

    public function show_all_posts(){
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$post = ((intval($this->params['page']) - 1) * 10);
		
		Doo::loadModel('Blog');
		$blog = new Blog;
		$blog->slug = $this->params['slug'];
		$one_blog = $blog->getOne();
		
		Doo::loadModel('Post');
		$posts = new Post;
		$posts->blog_id = $one_blog->id;
		
		$data['pagination'] = paginate($posts->count(), $post);
		
		$page = ((intval($this->params['page']) - 1) * 20);
		
		$data['posts'] = $posts->get_list_contents($one_blog->id, $page, 1, 20);
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		$data['pagination'] = paginate($posts->count(), $this->params['page'], 20);
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['blog'] = $one_blog->title;
		$data['blog_slug'] = $this->params['slug'];
		$data['page'] = $this->params['page'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - ".$this->params['slug'];
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'listPosts.html';
		$this->renderc('twig', $data);
    }

    public function create_one_post(){
    	include('protected/config/settings.php');
		include('protected/includes/textFunctions.php');
		include('protected/includes/postsFunctions.php');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(count($_POST) > 0){
			
			Doo::loadModel('Blog');
			$blog = new Blog;
			$blog->slug = $this->params['slug'];
			$one_blog = Doo::db()->find($blog, array('limit'=> 1));

			Doo::loadModel('Post');
			$post = new Post;
			
			$post->blogs_id = $one_blog->idblog;
			
			if(isset($_POST['featuredImageSelected']))
				$post->images_id = $_POST['featuredImageSelected'];
			else 
				$post->images_id = 0;
			
			$post_id = $post->insert();

			Doo::loadModel('Post_Content');
			$flag = true;
			foreach ($this->_application->idioms as $idiom){
				if($_POST['title'.$idiom->id] != ""){
					$post_content = new Post_Content;
					$post_content->title = $_POST['title'.$idiom->id];
					$post_content->text = str_replace("../", $project_url,str_replace("../../", $project_url,str_replace("../../../", $project_url,str_replace("../../../../", $project_url,str_replace("../../../../../", $project_url, $_POST['text'.$idiom->id])))));
					$post_content->slug = slug_generate($post_content->title);
					$post_content->description = $_POST['description'.$idiom->id];
					$post_content->keywords = $_POST['keywords'.$idiom->id];
					$post_content->idioms_id = $idiom->id;
					$post_content->posts_id = $post_id;
				
					if($flag){
						$slug = $post_content->slug;
						$flag = false;	
					}
					
					if(!insert_post($post_content)){
						$data['post'] = $_POST;
						$data['error'] = "Error al añadir la entrada en la base de datos";
					}
				}
			}

			save_gallery_images($_POST['gallery'], $post_id);
			if(!isset($data['error']))
				return $auth_url.'/blog/'.$this->params['slug'].'/editar/'.$slug.'/0/';
		}

		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists) || !isset($this->_application->idioms)){
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['blog_slug'] = $this->params['slug'];
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
    	$data['title'] = "Administración - Crear en ".str_replace('-', ' ', $this->params['slug']);
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'editPost.html';
		$this->renderc('twig', $data);
    }
	
	public function edit_one_post(){
		include('protected/config/settings.php');
		include('protected/includes/textFunctions.php');
		include('protected/includes/postsFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
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
		
		Doo::loadModel('Post');
		Doo::loadModel('Post_Content');
		Doo::loadModel('Post_Image');
		
		$post = new Post_Content;
		$post->slug = $this->params['slug2'];
		$post = Doo::db()->find($post)[0];
		$post_id = $post->posts_id;
		
		for($i = 1; $i <= count($this->_application->idioms); $i++){
			
			if(isset($_POST['title'.$i])){
				$post = new Post_Content;
				$post->pages_id = $post_id;
				$post->idioms_id = $i;
				$post_saved = Doo::db()->find($post)[0];
				
				$post->title = $_POST['title'.$i];
				$post->text = str_replace("../", $project_url,str_replace("../../", $project_url,str_replace("../../../", $project_url,str_replace("../../../../", $project_url,str_replace("../../../../../", $project_url, $_POST['text'.$i])))));
				$post->description = $_POST['description'.$i];
				$post->keywords = $_POST['keywords'.$i];
				
				if(isset($post_saved)){
					$post->id = $post_saved->id;
					$post->update();
				}else{
					$post->insert();
				}
			}
		}
		
		if(isset($_POST['title1']) || isset($_POST['title2']) || isset($_POST['title3'])){
			
			$post = new Post;
			$post->id = $post_id;
			$post->edit = date("Y-m-d h:m:s");
			
			if(isset($_POST['featuredImageSelected']))
				$post->images_id = $_POST['featuredImageSelected'];
			else 
				$post->images_id = 0;
			
			$post->update();
		}
		
		$post = new Post;
		$data['post'] = $post->get_post_all_contents($this->params['slug2']);
		
		if(isset($_POST['title'])){
			$post = new Post;
			$post->slug = $this->params['slug2'];
			$post_saved = $post->getOne();
			
			$post->title = $_POST['title'];
			$post->text = $_POST['text'];
			
			if($post_saved != false){
					$post->id = $post_saved->id;
					$post->update();
			}else{
				if($post->title != ""){
					$post->slug = slug_generate($post->title);
					if(!insert_post($post)){
						$data['post'] = $_POST;
						$data['error'] = "Error al añadir la entrada que no existía en la base de datos";
					}
				}
			}
		}
		
		if(isset($_POST['gallery']))
			save_gallery_images($_POST['gallery'], $post_id);
		else
			remove_gallery_images($post_id);
		
		Doo::loadModel('Blog');
		$blog = New Blog;
		$blog->slug = $this->params['slug'];
		$one_blog = $blog->getOne();
		
		$blog = New Blog;
		$blog->idblog = $one_blog->idblog;
		$data['blog_slugs'] = $blog->find();
		
		$post = new Post;
		$data['post'] = $post->get_post_all_contents($this->params['slug2']);
		
		if(strpos($_SERVER["HTTP_REFERER"], 'crear') > 0){
			$data['new'] = true;
		}
		
		$post_images = new Post_Image;
		$post_images->posts_id = $post_id;
		$data['images'] = $post_images->find();
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['page'] = $this->params['page'];
		$data['edit'] = true;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Administración - Editar ".$data['post'][0]->title;
		$data['description'] = "";
    	$data['view'] = $this->admin_view.'editPost.html';
		$this->renderc('twig', $data);
	}
	
	public function remove_one_post(){
		include('protected/config/settings.php');
		include('protected/includes/postsFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		Doo::loadModel('Post_Content');
		
		$post = new Post_Content;

        $post->slug = $this->params['slug2'];
        $post = Doo::db()->find($post)[0];
		
		$post_id = $post->posts_id;
		
		remove_gallery_images($post_id);
		
		$post = new Post_Content;
        $post->posts_id = $post_id;
        Doo::db()->delete($post);
		
		Doo::loadModel('Post');
		
		$post = new Post;
		$post->id = $post_id;
        Doo::db()->delete($post);
		
		return $auth_url."/blog/".$this->params['slug']."/listar/".$this->params['page'];
	}


	/* Functions relating to the public part */

	public function show_one_blog(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
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

		Doo::loadModel('Blog');
		$blog = new Blog;
		$blog->slug = $this->params['slug'];
		$one_blog = $blog->getOne();
		$data['blog'] = $one_blog;
		
		$post = ((intval($this->params['page']) - 1) * 10);
		
		Doo::loadModel('Post');
		$posts = new Post;
		$posts->blog_id = $one_blog->id;
		
		$data['pagination'] = paginate($posts->count(), $post);
		
		$page = ((intval($this->params['page']) - 1) * 10);
		
		$data['posts'] = $posts->get_list_contents($one_blog->idblog, $page, $this->_application->selected_idiom);
		
		$data['page'] = $this->params['page'];
		
		$data['header_links'] = $this->_application->header_links;
		$data['sidebar_links'] = $this->_application->sidebar_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['description'] = $this->_application->company->description;
		$data['keywords'] = $this->_application->company->keywords;
    	$data['view'] = $this->public_view.'listPosts.html';
		$this->renderc('twig', $data);
	}
	
	public function show_one_post(){
		include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		if(!is_numeric($this->params['page']))
			header('Location: ' . $project_url . 'error');
		
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
		
		Doo::loadModel('Blog');
		$blog = new Blog;
		$blog->slug = $this->params['slug'];
		$one_blog = $blog->getOne();
		$data['blog'] = $one_blog;
		
		Doo::loadModel('Post');
		$post = new Post;
		$one_post = $post->get_post_contents($this->params['slug2'], $this->_application->selected_idiom);
		
		if($one_post == false)
			header('Location: ' . $project_url . 'error');
		
		$data['post'] = $one_post;
		
		$data['page'] = $this->params['page'];
		
		Doo::loadModel('Post_Image');
		$post_images = new Post_Image;
		$post_images->posts_id = $data['post']->posts_id;
		$data['images'] = $post_images->find();
		
		$data['header_links'] = $this->_application->header_links;
		$data['sidebar_links'] = $this->_application->sidebar_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['title'] = $one_post->title . " - " . $this->_application->company->name;
		$data['description'] = $data['post']->description;
		$data['keywords'] = $this->_application->company->keywords;
		
		if(isset($this->_application->company->keywords) && isset($data['post']->keywords))
			$data['keywords'] .= ", ";
		
		$data['keywords'] .= $data['post']->keywords;
    	$data['view'] = $this->public_view.'showPost.html';

		$this->renderc('twig', $data);
	}
}
?>
