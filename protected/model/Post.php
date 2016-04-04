<?php
/**
 * Coconut Post Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Post extends DooModel{

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
     * @var int Max length is 11.
     */
    public $blogs_id;
	
	/**
     * @var tinyint Max length is 1. Acts as boolean.
     */
    public $images_id;

    public $_table = 'posts';
    public $_primarykey = 'id';
    public $_fields = array('id','created','edit','blogs_id','images_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

	public function get_list_contents($blog, $page, $idiom = 1, $limit = 10){
        return Doo::db()->relate('Post_Content', __CLASS__, array('limit' => $page.','.$limit, 'select' => 'posts.id, posts.images_id, posts.created, posts_content.title, posts_content.slug, posts_content.text', 'where' => 'idioms_id = '.$idiom.' and blogs_id = '.$blog, 'desc' => 'id'));
    }
	
	public function get_post_all_contents($slug){
		Doo::loadModel('Post_Content');
		$post_content = new Post_Content;
		$post_content->slug = $slug;
		$one_post_content = $post_content->find();
        return Doo::db()->relate('Post_Content', __CLASS__, array('where' => 'posts_id = '.$one_post_content[0]->posts_id));
    }
	
	public function get_post_contents($slug, $idiom = 1){
        return Doo::db()->relate('Post_Content', __CLASS__, array('where' => 'slug = "'.$slug.'" and idioms_id = '.$idiom))[0];
    }

	public function search_post($search, $idiom = 1, $page = 0, $limit = 10){
		return Doo::db()->relate('Post_Content', __CLASS__, array('limit' => $page.','.$limit, 'select' => 'posts.id, posts.images_id, posts.created, posts_content.title, posts_content.slug, posts_content.text', 'where' => 'idioms_id = '.$idiom.' and (title  like "%'.$search.'%" or text like "%'.$search.'%")'));
	}

	public function count_search_post($search, $idiom = 1){
		return count(Doo::db()->relate('Post_Content', __CLASS__, array('where' => 'idioms_id = '.$idiom.' and (title  like "%'.$search.'%" or text like "%'.$search.'%")')));
	}
}
?>
