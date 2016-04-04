<?php
/**
 * Coconut AdminController
 * Functions for login, logout, show the help and show the index admin panel.
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class AdminController extends DooController{

    public function index(){
    	include('protected/config/settings.php');
		$this->_application = Doo::session("web");
		if($this->_application->auth){
			return $auth_url.'/panel/';
		}

    	if(isset($_POST['username']) && isset($_POST['password'])){
    		Doo::loadModel('User');
			$user = new User;
			$user->username = $_POST['username'];
			$user->password = md5($_POST['password']);

			$user_exists = $user->count();
			
			if($user_exists){
				$this->_application->auth  = true;
				$this->_application->username = $_POST['username'];
				return $auth_url.'/panel/';
			}else{
				$data['error'] = "El nombre de usuario o la contraseña son incorrectos";
				$data['auth'] = false;
			}
    	}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists)){
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}

		$data['company'] = $this->_application->company;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
    	$data['title'] = "Administración";
		$data['description'] = "";
    	$data['view'] = 'admin/index.html';
		$this->renderc('twig', $data);
    }
	
	public function index_panel(){
		include('protected/config/settings.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists)){
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
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Panel - Administración";
		$data['description'] = "";
    	$data['view'] = 'admin/adminPanel.html';
		$this->renderc('twig', $data);
    }
	
	public function edit_company(){
		include('protected/config/settings.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		Doo::loadModel('Company');
		Doo::loadModel('Company_Content');
		
		if(isset($_POST['name']) && isset($_POST['address']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['telephone']) && isset($_POST['email'])){
			$company = new Company;
			$company->id = 1;
			$company->name = $_POST['name'];
			$company->address = $_POST['address'];
			$company->latitude = $_POST['latitude'];
			$company->longitude = $_POST['longitude'];
			$company->telephone = $_POST['telephone'];
			$company->telephone2 = $_POST['telephone2'];
			$company->email = $_POST['email'];
			$company->facebook = $_POST['facebook'];
			$company->twitter = $_POST['twitter'];
			$company->youtube = $_POST['youtube'];
			$company->linkedin = $_POST['linkedin'];
			$company->pinterest = $_POST['pinterest'];
			$company->instagram = $_POST['instagram'];
        	$company->update();
			
			foreach($this->_application->idioms as $idiom){
				$company_content = new Company_Content;
				$company_content->id = $idiom->id;
				$company_content->idioms_id = $idiom->id;
				$company_content->slogan = $_POST["slogan".$idiom->id];
				$company_content->description = $_POST["description".$idiom->id];
				$company_content->keywords = $_POST["keywords".$idiom->id];
				$company_content->update();
			}
			
		}
		
		$company = new Company;
		$company->id = 1;
		$data['oneCompany'] = $company->getOne();
		$this->_application->company = $company->name;
		
		$company_content = new Company_Content;
		$data['oneCompanyContent'] = $company_content->find();
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['idioms'] = $this->_application->idioms;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Panel - Administración";
		$data['description'] = "";
    	$data['view'] = 'admin/editCompany.html';
		$this->renderc('twig', $data);
    }
	
	public function logout(){
		include('protected/config/settings.php');
		$this->_application = Doo::session("web");
    	$this->_application->destroy();
    	return $auth_url;
    }
	
	public function stay_informed(){
		include('protected/config/settings.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists)){
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
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Panel - Mantente Informado";
		$data['description'] = "";
    	$data['view'] = 'admin/stayInformed.html';
		$this->renderc('twig', $data);
	}
	
	public function download_emails(){
		Doo::loadModel('Email');
		$email = new Email;
		$emails = $email->find();
		
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="listadoEmailsSuscritos.csv"');

		$fp = fopen('php://output', 'w');
		
		fputcsv($fp, array("", "Fecha", "Email"));
		
		foreach($emails as $email){
			$one_email = array($email->id, $email->created, $email->address);
			fputcsv($fp, $one_email);
		}
		
		fclose($fp);
		exit;
    }
	
	public function show_help(){
		include('protected/config/settings.php');
		$this->_application = Doo::session("web");
		if(!$this->_application->auth){
			return $auth_url;
		}
		
		if(!isset($this->_application->company) || !isset($this->_application->blogs) || !isset($this->_application->lists)){
			include('protected/includes/generalFunctions.php');
			$session = start_admin_session();
			$this->_application->company = $session[0];
			$this->_application->blogs = $session[1];
			$this->_application->lists = $session[2];
			$this->_application->idioms = $session[3];
		}

		Doo::loadModel('Help');
		$help = new Help;
		$help->id = $this->params['section'];
		$data['help'] = $help->getOne();
		
		$data['help']->text = str_replace("{{url2}}", $project_url, $data['help']->text);
		
		$data['company'] = $this->_application->company;
		$data['blogs'] = $this->_application->blogs;
		$data['lists'] = $this->_application->lists;
		$data['url'] = $auth_url;
		$data['url2'] = $project_url;
		$data['user'] = $this->_application->username;
		$data['title'] = "Panel - Ayuda";
		$data['description'] = "";
    	$data['view'] = 'admin/help.html';
		$this->renderc('twig', $data);
	}

}
?>
