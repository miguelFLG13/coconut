<?php

	$host_url = 'http://'.$_SERVER['HTTP_HOST'].'/';
	$local_url = 'cms/';
	$project_url = $host_url.$local_url;
	$auth_url = $host_url.$local_url.'admin';
	
	$idioms =['es_ES.utf8', 'en_US.utf8']; //Complete the idioms with the locale
	
	$blog_home = 1; //Options [0-Idblog in db]
	
	$slider = false;
	
	$pages_name = array('Páginas', 'Pages'); //Pages name in the index

    $posts_home = 2;

	$pages_home = 0;
	
	/* Load Obligatory Models */
	Doo::loadModel('Blog');
	Doo::loadModel('oneList');
	Doo::loadModel('Idiom');

?>
