<?php
/**
 * Coconut Post Image Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Post_Image extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $posts_id;

    /**
     * @var int Max length is 11.
     */
    public $images_id;

    public $_table = 'posts_has_images';
    public $_primarykey = array('posts_id','images_id');
    public $_fields = array('posts_id','images_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
