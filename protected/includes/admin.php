<?php
/**
 * Coconut Function for admin session
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

function start_admin_session(){
	Doo::loadModel('Company');
 	$company = new Company;
	$company->id = 1;
	$company = $company->getOne();
	$company_name = $company->name;
	
	Doo::loadModel('Blog');
	$blog = new Blog;
	$blogs = $blog->find();
	
	Doo::loadModel('oneList');
	$list = new oneList;
	$lists = $list->find();
	
	return array($company_name, $blogs, $lists);
}

?>
