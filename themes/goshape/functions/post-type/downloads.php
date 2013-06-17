<?php
/*

	POST-TYPE: Downloads

*/
# Chama a função downloads_register ao iniciar o paínel admin do WP
add_action('init', 'downloads_register');

# Registramos método para gerar o novo post-type
function downloads_register() {
 
	$labels = array(
		'name' => _x('Downloads', 'post type general name'),
		'singular_name' => _x('Downloads', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'Downloads'),
		'add_new_item' => __('Add Novo Item'),
		'edit_item' => __('Editar Item'),
		'new_item' => __('Novo Item'),
		'view_item' => __('Ver Item'),
		'search_items' => __('Procurar Item'),
		'not_found' =>  __('Nenhum item encontrado'),
		'not_found_in_trash' => __('Nenhum item encontrado na lixeira'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_bloginfo( 'template_url' ) . '/recursos/images/post-type-thumb/downloads.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title'),
	  );
 
	register_post_type( 'Downloads' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "Downloads_custom_columns");
add_filter("manage_edit-Downloads_columns", "Downloads_edit_columns");
 
function Downloads_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Download",
    "data" => "Data"
  );
 
  return $columns;
}

function Downloads_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "data": echo get_the_date(); break;
  }
}
?>