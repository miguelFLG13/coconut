<?php
/**
 * Coconut Page Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Page extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var datetime
     */
    public $created;

    /**
     * @var datetime
     */
    public $edit;

	/**
     * @var tinyint Max length is 1. Acts as boolean.
     */
    public $home;

	/**
     * @var tinyint Max length is 1. Acts as boolean.
     */
    public $featured_image;

    /**
     * Not in page table in bbdd, use only for lists or links with the table lists_has_pages or links_has_pages.
     */
    public $selected;
	
	/**
     * @var tinyint Max length is 1. Acts as boolean.
     */
    public $images_id;

    public $_table = 'pages';
    public $_primarykey = 'id';
    public $_fields = array('id','created','edit','home','featured_image','images_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }
	
    public function get_list_contents($page, $idiom = 1, $limit = 20, $home = 0){
    	if($page > -1){
    		if($home)
				return Doo::db()->relate('Page_Content', __CLASS__, array('limit' => $page.','.$limit, 'select' => 'pages.id, pages.featured_image, pages.images_id, pages.created, pages_content.title, pages_content.slug, pages_content.text, pages.home', 'where' => 'idioms_id = '.$idiom.' AND home = 1'));
			else
        		return Doo::db()->relate('Page_Content', __CLASS__, array('limit' => $page.','.$limit, 'select' => 'pages.id, pages.featured_image, pages.images_id, pages.created, pages_content.title, pages_content.slug, pages_content.text, pages.home', 'where' => 'idioms_id = '.$idiom));
		}else
			return Doo::db()->relate('Page_Content', __CLASS__, array('where' => 'idioms_id = '.$idiom));
    }
	
	public function get_page_all_contents($slug){
		Doo::loadModel('Page_Content');
		$page_content = new Page_Content;
		$page_content->slug = $slug;
		$one_page_content = $page_content->find();
        return Doo::db()->relate('Page_Content', __CLASS__, array('where' => 'pages_id = '.$one_page_content[0]->pages_id));
    }
	
	public function get_one_page($slug, $idiom = 1){
		return Doo::db()->relate('Page_Content', __CLASS__, array('where' => 'idioms_id = '.$idiom.' and slug = '.$slug));
    }

	public function search_page($search, $idiom = 1, $page = 0, $limit = 10){
		return Doo::db()->relate('Page_Content', __CLASS__, array('limit' => $page.','.$limit, 'select' => 'pages.id, pages.images_id, pages.created, pages_content.title, pages_content.slug, pages_content.text', 'where' => 'idioms_id = '.$idiom.' and (title  like "%'.$search.'%" or text like "%'.$search.'%")'));
	}
	
	public function count_search_page($search, $idiom = 1){
		return count(Doo::db()->relate('Page_Content', __CLASS__, array('where' => 'idioms_id = '.$idiom.' and (title  like "%'.$search.'%" or text like "%'.$search.'%")')));
	}
}
?>
