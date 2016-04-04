<?php
/**
 * Coconut Link Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Link extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var datetime
     */
    public $created;

	/**
     * @var tinyint Max length is 1. Acts as boolean.
     */
    public $is_header;
	
	/**
     * @var tinyint Max length is 1.
     */
    public $link_order;

	/**
     * @var int Max length is 11.
     */
    public $parent_id;
	
	/**
     * @var varchar Max length is 100.
     */
    public $name;

    /**
     * @var varchar Max length is 200.
     */
    public $url;

    /**
     * @var int Max length is 11.
     */
    public $idioms_id;
	
	/**
     * @var LinkContent.
     */
    public $content;

    public $_table = 'links';
    public $_primarykey = 'id';
    public $_fields = array('id','created','is_header','link_order','parent_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
