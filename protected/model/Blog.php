<?php
/**
 * Coconut Blog Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Blog extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $idblog;

    /**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var text
     */
    public $text;
	
	/**
     * @var varchar Max length is 100.
     */
    public $slug;

    /**
     * @var int Max length is 11.
     */
    public $idioms_id;
	
    public $_table = 'blogs';
    public $_primarykey = 'id';
    public $_fields = array('id','idblog','title','slug','idioms_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
