<?php
/**
 * Coconut Company Content Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Company_Content extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $slogan;
	
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

    public $_table = 'company_content';
    public $_primarykey = 'id';
    public $_fields = array('id','slogan','description','keywords','idioms_id');
	
	public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }
}
?>
