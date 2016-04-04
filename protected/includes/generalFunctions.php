<?php
/**
 * Coconut Functions for general tasks
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadModel('Company');

function paginate($pagination_number, $page, $limit = 10){

	$page_selected = explode('.', (string)(($pagination_number / $limit) + 1));

	$total = $page_selected[0];

    if($total == 1)
        return array();
	elseif($total == 2)
        return array(1, 2);
	
    if($page == 1)
        return array(1, 2, "...",$total);
	elseif($page == $total)
        return array(1, "...", ($total-1), $total);
	elseif($page == 2)
        return array(1, 2, 3, "...", $total);
	elseif($page == ($total-1))
        return array(1, "...", ($total-2), ($total-1), $total);
    else
		if(($page-2) == 1)
        	return array(1, ($page-1), $page, ($page+1), "...", $total);
		elseif(($page+2) == $total)
			return array(1, "...", ($page-1), $page, ($page+1), $total);
		else
			return array(1, "...", ($page-1), $page, ($page+1), "...", $total);
}

function start_admin_session($idiom = 1){
	Doo::loadModel('Company');
 	$company = new Company;
	$company->id = 1;
	$company = $company->getOne();
	
	Doo::loadModel('Company_Content');
 	$company_content = new Company_Content;
	$company_content->id = $idiom;
	$company_content = $company_content->getOne();
	
	$company->slogan = $company_content->slogan;
	$company->description = $company_content->description;
	$company->keywords = $company_content->keywords;
	
	Doo::loadModel('Blog');
	$blog = new Blog;
	$blog->idioms_id = $idiom;
	$blogs = $blog->find();
	
	Doo::loadModel('oneList');
	$list = new oneList;
	$lists = $list->find();
	
	Doo::loadModel('Idiom');
	$idiom = new Idiom;
	$idioms = $idiom->find();
	
	return array($company, $blogs, $lists, $idioms);
}

function start_public_session($idiom = 1){
	$result = start_admin_session($idiom);
	
	if(!isset($_COOKIE["subscribe"]))
		$_COOKIE["subscribe"] = "None";
	
	array_push($result, obtain_links(1, $idiom), obtain_links(0, $idiom), $_COOKIE["subscribe"]);
	
	return $result;
}

function obtain_links($type, $idiom = 1){
	include('protected/config/settings.php');
	
	Doo::loadModel('Link');
	$link = new Link;
	$link->parent_id = 0;
	$link->is_header = $type;
	$parent_links = $link->find(array('asc' => 'link_order'));

	$i = 0;
	$links = array();
	foreach($parent_links as $parent_link){ //Order the links, first one parent, then all his sons
		$links[$i] = $parent_link;
		$son_link = new Link;
		$son_link->is_header = $type;
		$son_link->parent_id = $parent_link->id;
		$son_links = $son_link->find(array('asc' => 'link_order'));
		foreach($son_links as $son_link){
			$i++;
			$links[$i] = $son_link;
		}
		$i++;
	}
	
	$result_links = array();
	
	Doo::loadModel('Page');
	
	Doo::loadModel('Page_Content');
	
	Doo::loadModel('Link_Content');
	
	Doo::loadModel('Link_Page');
	
	for($i = 0; $i < count($links); $i++){ //Take the correct url in the page links
		
		$link_has_page = new Link_Page;
		$link_has_page->links_id = $links[$i]->id;
		$link_with_page = $link_has_page->find();

		if(count($link_with_page) > 0){
			$page = New Page_Content;
			$page->pages_id = $link_with_page[0]->pages_id;
			$page->idioms_id = $idiom;
			$one_page = $page->find();
			
			$link = new Link;
			$link->id = $links[$i]->id;
			$link->name = $one_page[0]->title;
			$link->url = $project_url."pagina/".$one_page[0]->slug;
			$link->link_order = $links[$i]->link_order;
			$link->parent_id = $links[$i]->parent_id;
		}else{
			Doo::loadModel('Link_List');
			
			$link_has_list = new Link_List;
			$link_has_list->links_id = $links[$i]->id;
			$link_with_list = $link_has_list->getOne();
			
			if($link_with_list != false){
				Doo::loadModel('List_Content');
				$list = New List_Content;
				$list->lists_id = $link_with_list->lists_id;
				$list->idioms_id = $idiom;
				$one_list = $list->getOne();
			
				$link = new Link;
				$link->id = $links[$i]->id;
				$link->name = $one_list->title;
				$link->url = $project_url."lista/".$one_list->slug."/1/";
				$link->link_order = $links[$i]->link_order;
				$link->parent_id = $links[$i]->parent_id;
			}else{
				Doo::loadModel('Link_Blog');
			
				$link_has_blog = new Link_Blog;
				$link_has_blog->links_id = $links[$i]->id;
				$link_with_blog = $link_has_blog->getOne();
			
				if($link_with_blog != false){
					Doo::loadModel('Blog');
					$blog = New Blog;
					$blog->idblog = $link_with_blog->blogs_id;
					$blog->idioms_id = $idiom;
					$one_blog = $blog->getOne();
			
					$link = new Link;
					$link->id = $links[$i]->id;
					$link->name = $one_blog->title;
					$link->url = $project_url."blog/".$one_blog->slug."/1/";
					$link->link_order = $links[$i]->link_order;
					$link->parent_id = $links[$i]->parent_id;
				}else{
					$link_content = New Link_Content;
					$link_content->links_id = $links[$i]->id;
					$link_content->idioms_id = $idiom;
					$one_link_content = $link_content->find();
			
					$link = new Link;
					$link->id = $links[$i]->id;
					$link->name = $one_link_content[0]->name;
					$link->url = $one_link_content[0]->url;
					$link->link_order = $links[$i]->link_order;
					$link->parent_id = $links[$i]->parent_id;
				}
			}
		}
		
		array_push($result_links, $link);		
	}
	
	return $result_links;
}
?>
