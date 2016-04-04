<?php
/**
 * Coconut Company Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Company extends DooModel{

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
    public $address;
	
    /**
     * @var decimal 8,5.
     */
    public $latitude;
	
    /**
     * @var decimal 8,5.
     */
    public $longitude;
	
	/**
     * @var varchar Max length is 15.
     */
    public $telephone;
	
	/**
     * @var varchar Max length is 15.
     */
    public $telephone2;
	
	/**
     * @var varchar Max length is 75.
     */
    public $email;

	/**
     * @var varchar Max length is 100.
     */
    public $facebook;
	
	/**
     * @var varchar Max length is 100.
     */
    public $twitter;
	
	/**
     * @var varchar Max length is 100.
     */
    public $youtube;

	/**
     * @var varchar Max length is 100.
     */
    public $linkedin;
	
	/**
     * @var varchar Max length is 100.
     */
    public $pinterest;
	
	/**
     * @var varchar Max length is 100.
     */
    public $instagram;
	
	/**
     * @var varchar Max length is 100.
     */
    public $slogan;
	
	/**
     * @var varchar Max length is 250.
     */
    public $description;
	
	/**
     * @var varchar Max length is 20.
     */
    public $keywords;

    public $_table = 'company';
    public $_primarykey = 'id';
    public $_fields = array('id','name','address','latitude','longitude','telephone','telephone2','email','facebook','twitter','youtube','linkedin','instagram','pinterest','description','keywords');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
