<?php
/**
 * Coconut List Content Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class List_Content extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var varchar Max length is 100.
     */
    public $slug;
		
	/**
     * @var varchar Max length is 160.
     */
    public $description;
	
	/**
     * @var varchar Max length is 200.
     */
    public $keywords;
	
	/**
     * @var int Max length is 11.
     */
    public $idioms_id;
	
	/**
     * @var int Max length is 11.
     */
    public $lists_id;

    public $_table = 'lists_content';
    public $_primarykey = 'id';
    public $_fields = array('id','title','slug','description','keywords','idioms_id','lists_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
