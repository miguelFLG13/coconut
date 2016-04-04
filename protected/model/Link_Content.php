<?php
/**
 * Coconut Link Content Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Link_Content extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

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
     * @var int Max length is 11.
     */
    public $links_id;

    public $_table = 'links_content';
    public $_primarykey = 'id';
    public $_fields = array('id','name','url','idioms_id','links_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
