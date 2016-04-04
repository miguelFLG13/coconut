<?php
/**
 * Coconut Functions for posts
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

function insert_post($post_content){
	
	Doo::loadModel('Post_Content');
	
	try{
		$post_content->insert();
		return true;
	}catch(Exception $e){
    	try { //If the admin try add a content with duplicate title
    		$random = rand(1, 99);
			$post_content->title .=  ' '.$random;
    		$post_content->slug .= '-'.$random;
    		$post_content->insert();
			return true;
		}catch(Exception $e2){
			return false;
		}
	}
}

function save_gallery_images($images, $post_id){
	Doo::loadModel('Post_Image');
	
	$post_image = New Post_Image;
	$post_image->posts_id = $post_id;
	
	$post_image->delete();
	
	foreach($images as $image) {
		$post_image->images_id = $image;
		$post_image->insert();
	}
	
}

function remove_gallery_images($post_id){
	Doo::loadModel('Post_Image');
	
	$post_image = New Post_Image;
	$post_image->posts_id = $post_id;
	
	$post_image->delete();
}
?>
