<?php

$route['*']['/error'] = array('ErrorController', 'index');

/* Public zone URLs */
$route['*']['/'] = array('MainController', 'index');
$route['*']['/idioma/:idiom'] = array('MainController', 'change_idiom'); //Change the web idiom
$route['*']['/suscribete'] = array('MainController', 'subscribe_email'); //Subscribe a email


$route['*']['/pagina/:slug'] = array('PagesController', 'show_one_page'); //Show one page


$route['*']['/blog/:slug/:page'] = array('PostController', 'show_one_blog'); //Show blog posts
$route['*']['/blog/:slug/post/:slug2/:page'] = array('PostController', 'show_one_post'); //Show one post


$route['*']['/lista/:slug/:page'] = array('ListsController', 'show_one_list'); //Show list pages
$route['*']['/lista/:slug/:page/pagina/:slug2'] = array('PagesController', 'show_one_page'); //Show one page


$route['*']['/buscar'] = array('MainController', 'search'); //Search pages or posts
$route['*']['/buscar/:search/:page1/:page2'] = array('MainController', 'search'); //Search pages or posts


$route['*']['/contacto'] = array('MainController', 'contact_page'); //Contact dinamic page


/* Admin zone URLs */
$route['*']['/admin/'] = array('AdminController', 'index'); //Login
$route['*']['/admin/logout'] = array('AdminController', 'logout'); //Logout
$route['*']['/admin/panel'] = array('AdminController', 'index_panel'); //User panel index
$route['*']['/admin/ayuda/:section'] = array('AdminController', 'show_help'); //Help section


$route['*']['/admin/contacto/editar'] = array('AdminController', 'edit_company'); //Edit the company info


$route['*']['/admin/blog/:slug/listar/:page'] = array('PostController', 'show_all_posts'); //View posts of one blog
$route['*']['/admin/blog/:slug/crear'] = array('PostController', 'create_one_post'); //Create one post
$route['*']['/admin/blog/:slug/editar/:slug2/:page'] = array('PostController', 'edit_one_post'); //Edit one post
$route['*']['/admin/blog/:slug/eliminar/:slug2/:page'] = array('PostController', 'remove_one_post'); //Delete one post


$route['*']['/admin/pagina/listar/:page'] = array('PagesController', 'show_all_pages'); //View all pages
$route['*']['/admin/pagina/crear'] = array('PagesController', 'create_one_page'); //Create one page
$route['*']['/admin/pagina/editar/:slug/:page'] = array('PagesController', 'edit_one_page'); //Edit one page
$route['*']['/admin/pagina/eliminar/:slug/:page'] = array('PagesController', 'remove_one_page'); //Delete one page


$route['*']['/admin/listado/listar'] = array('ListsController', 'show_all_lists'); //View all lists
$route['*']['/admin/listado/crear'] = array('ListsController', 'create_one_list'); //Create one list
$route['*']['/admin/listado/editar/:slug/:created'] = array('ListsController', 'edit_one_list'); //Attach or detach a page to a list
$route['*']['/admin/listado/editar/:slug'] = array('ListsController', 'edit_one_list'); //Attach or detach a page to a list
$route['*']['/admin/listado/eliminar/:slug'] = array('ListsController', 'remove_one_list'); //Delete one list


$route['*']['/admin/enlaces/listar/:type'] = array('LinksController', 'edit_one_link'); //Attach a link (type=1 is header, type=0 is sidebar)
$route['*']['/admin/enlaces/eliminar/:id/:type'] = array('LinksController', 'remove_one_link'); //Detach a link


$route['*']['/admin/mantenteInformado'] = array('AdminController', 'stay_informed'); //Enter section Stay Informed
$route['*']['/admin/descargarEmails'] = array('AdminController', 'download_emails'); //Download emails list


$route['*']['/admin/galeria/:page'] = array('ImagesController', 'show_all_images'); //Show gallery
$route['*']['/admin/galeria/imagen/:id/:page'] = array('ImagesController', 'view_image'); //View a image
$route['*']['/admin/galeria/imagen/subir'] = array('ImagesController', 'upload_image'); //Upload images
$route['*']['/admin/galeria/imagen/eliminar/:id/:page'] = array('ImagesController', 'remove_image'); //View a image
$route['*']['/admin/cargador/imagenes/:type/:page'] = array('ImagesController', 'image_uploader'); //Image uploader

$route['*']['/admin/carrusel'] = array('ImagesController', 'edit_slider_images'); //Edit slider images
?>
