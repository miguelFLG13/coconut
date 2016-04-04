<?php
/**
 * Coconut Function for pages
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

function insert_page($page_content){
	
	Doo::loadModel('Page_Content');
	
 	try{
		$page_content->insert();
		return true;
	}catch(Exception $e){
    	try { //If the admin try add a content with duplicate title
    		$random = rand(1, 99);
			$page_content->title .=  ' '.$random;
    		$page_content->slug .= '-'.$random;
    		$page_content->insert();
			return true;
		}catch(Exception $e2){
			return false;
		}
	}
}

function save_gallery_images($images, $page_id){
	Doo::loadModel('Page_Image');
	
	$page_image = New Page_Image;
	$page_image->pages_id = $page_id;
	
	$page_image->delete();
	
	foreach($images as $image) {
		$page_image->images_id = $image;
		$page_image->insert();
	}
	
}

function remove_gallery_images($page_id){
	Doo::loadModel('Page_Image');
	
	$page_image = New Page_Image;
	$page_image->pages_id = $page_id;
	
	$page_image->delete();
}
?>
