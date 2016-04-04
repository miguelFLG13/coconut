<?php
/**
 * Coconut Idiom Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Idiom extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $name;

    public $_table = 'idioms';
    public $_primarykey = 'id';
    public $_fields = array('id','name');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
