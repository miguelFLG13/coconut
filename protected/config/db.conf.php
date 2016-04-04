<?php
/**
 * Example Database connection settings and DB relationship mapping
 * $dbmap[Table A]['has_one'][Table B] = array('foreign_key'=> Table B's column that links to Table A );
 * $dbmap[Table B]['belongs_to'][Table A] = array('foreign_key'=> Table A's column where Table B links to );
 */
 
$dbmap['Blog']['has_many']['Post'] = array('foreign_key'=>'blogs_id');

$dbmap['Post']['has_one']['Post_Content'] = array('foreign_key'=>'posts_id');
$dbmap['Post_Content']['belongs_to']['Post'] = array('foreign_key'=>'id');
$dbmap['Post_Content']['has_one']['Idiom'] = array('foreign_key'=>'idioms_id');

$dbmap['Page']['has_one']['Page_Content'] = array('foreign_key'=>'pages_id');
$dbmap['Page_Content']['belongs_to']['Page'] = array('foreign_key'=>'id');
$dbmap['Page_Content']['has_one']['Idiom'] = array('foreign_key'=>'idioms_id');

$dbmap['oneList']['has_many']['Page'] = array('foreign_key'=>'lists_id', 'through'=>'lists_has_pages');
$dbmap['Page']['has_many']['oneList'] = array('foreign_key'=>'pages_id', 'through'=>'lists_has_pages');
$dbmap['oneList']['has_one']['List_Content'] = array('foreign_key'=>'lists_id');
$dbmap['List_Content']['belongs_to']['oneList'] = array('foreign_key'=>'id');
$dbmap['List_Content']['has_one']['Idiom'] = array('foreign_key'=>'idioms_id');

$dbmap['Link']['has_many']['Page'] = array('foreign_key'=>'links_id', 'through'=>'links_has_pages');
$dbmap['Link']['has_one']['Link_Content'] = array('foreign_key'=>'links_id');
$dbmap['Link_Content']['belongs_to']['Link'] = array('foreign_key'=>'id');
$dbmap['Link_Content']['has_one']['Idiom'] = array('foreign_key'=>'idioms_id');
$dbmap['Idiom']['belongs_to']['Link_Content'] = array('foreign_key'=>'id');

/**
 * Database settings are case sensitive.
 * To set collation and charset of the db connection, use the key 'collate' and 'charset'
 * array('localhost', 'database', 'root', '1234', 'mysql', true, 'collate'=>'utf8_unicode_ci', 'charset'=>'utf8'); 
 */

//$dbconfig['dev'] = array('localhost', 'nicaragua', 'root', '', 'mysql', true);
$dbconfig['prod'] = array('localhost', 'migueljg2', 'root', '', 'mysql', true);
?>
