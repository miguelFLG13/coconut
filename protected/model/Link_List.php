<?php
/**
 * Coconut Link List Model
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

Doo::loadCore('db/DooModel');

class Link_List extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $links_id;

    /**
     * @var int Max length is 11.
     */
    public $lists_id;

    public $_table = 'links_has_lists';
    public $_primarykey = array('links_id','lists_id');
    public $_fields = array('links_id','lists_id');


    public function  __construct($data=null) {
        parent::__construct( $data );
        parent::setupModel(__CLASS__);
    }

}
?>
