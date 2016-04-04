<?php
/**
 * Coconut ErrorController
 * Controller for handling errors
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */
class ErrorController extends DooController{

    public function index(){
    	Doo::loadModel('Link');
		
    	include('protected/config/settings.php');
		include('protected/includes/generalFunctions.php');
		
		$this->_application = Doo::session("web");
		
		$this->_application->url = $_SERVER['REQUEST_URI'];
		
		if(!isset($this->_application->idiom)){
			$this->_application->idiom = 1;
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
		
        $data['header_links'] = $this->_application->header_links;
		$data['idioms'] = $this->_application->idioms;
		$data['selected_idiom'] = $this->_application->selected_idiom;
		$data['subscribe'] = $this->_application->subscribe;
		$data['slider'] = $slider;
		$data['url'] = $project_url;
		$data['company'] = $this->_application->company;
		$data['description'] = $this->_application->company->description;
		$data['keywords'] = $this->_application->company->keywords;
    	$data['view'] = 'public/error.html';

		$this->renderc('twig', $data);
    }
	

}
?>
