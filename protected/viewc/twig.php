<?php

/**
 * Coconut Adapter between dooPHP and Twig
 * @date 26-06-2015
 * @author Miguel Jimenez Garcia
 *
 */
	require_once 'protected/viewc/Twig/Autoloader.php';
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('protected/view/');
	$twig = new Twig_Environment($loader, array('cache' => 'protected/viewc/compilation_cache'));
	
	$escaper = new Twig_Extension_Escaper(false);
	$twig->addExtension($escaper);
	
	$twig->addExtension(new Twig_Extensions_Extension_I18n());
	
	if(!isset($this->data['selected_idiom']))
		$this->data['selected_idiom'] = 1;
	
	include('protected/config/settings.php');
	putenv('LC_ALL='.$idioms[($this->data['selected_idiom']-1)]);
	setlocale(LC_ALL, $idioms[($this->data['selected_idiom']-1)]);

	bindtextdomain('messages', 'protected/translations');
	bind_textdomain_codeset('messages', 'UTF-8');
	
	textdomain('messages');
	echo $twig->render($this->data['view'], $this->data);
?>
