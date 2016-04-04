<?php
/**
 * Coconut Link_Blog Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Link_Blog extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $links_id;

    /**
     * @var int Max length is 11.
     */
    public $blogs_id;

    public $_table = 'links_has_blogs';
    public $_primarykey = array('links_id','blogs_id');
    public $_fields = array('links_id','blogs_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
