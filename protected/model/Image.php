<?php
/**
 * Coconut Image Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Image extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var datetime
     */
    public $created;

	/**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var text
     */
    public $text;
	
	/**
     * @var tinyint Max length is 1. Acts as boolean.
     */
    public $slider;

    public $_table = 'images';
    public $_primarykey = 'id';
    public $_fields = array('id','created','title','text','slider');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
