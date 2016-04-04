<?php
/**
 * Coconut User Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class User extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 10.
     */
    public $username;
	
	/**
     * @var varchar Max length is 32.
     */
    public $password;

    public $_table = 'users';
    public $_primarykey = 'id';
    public $_fields = array('id','username','password');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
