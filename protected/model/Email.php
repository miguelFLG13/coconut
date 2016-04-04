<?php
/**
 * Coconut Email Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Email extends DooModel{

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
    public $addess;

    public $_table = 'emails';
    public $_primarykey = 'id';
    public $_fields = array('id','created','address');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
