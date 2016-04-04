<?php
/**
 * Coconut Functions to handle text
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

function slug_generate($string){
 	$string = trim($string);
	$string = strtolower($string); 
 	$string = preg_replace("/á/","a",$string);
 	$string = preg_replace("/é/","e",$string);
 	$string = preg_replace("/í/","i",$string);
 	$string = preg_replace("/ó/","o",$string);
 	$string = preg_replace("/ú/","u",$string); 
	$string = preg_replace("/ñ/","n",$string);
	$string = preg_replace("/ç/","c",$string);
 	$string = preg_replace("/[ \t\n\r]+/", " ", $string);
 	$string = preg_replace("/[^ A-Za-z0-9_]/", "", $string);
	$string = trim($string); 
 	$string = str_replace(" ", "-", $string);
 	return $string;
}

?>
