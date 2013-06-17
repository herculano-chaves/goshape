<?php
/*

	POST-TYPE: Apoiadores

*/
# Chama a função apoiadores_register ao iniciar o paínel admin do WP
add_action('init', 'apoiadores_register');

# Registramos método para gerar o novo post-type
function apoiadores_register() {
 
	$labels = array(
		'name' => _x('Apoiadores', 'post type general name'),
		'singular_name' => _x('Apoiador', 'post type singular name'),
		'add_new' => _x('Add Novo Apoiador', 'apoiadores'),
		'add_new_item' => __('Add Novo Apoiador'),
		'edit_item' => __('Editar Apoiador'),
		'new_item' => __('Novo Apoiador'),
		'view_item' => __('Ver Apoiador'),
		'search_items' => __('Procurar Apoiador'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/apoiadores.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'apoiadores' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "apoiadores_custom_columns");
add_filter("manage_edit-apoiadores_columns", "apoiadores_edit_columns");
 
function apoiadores_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Apoiadores",
    "descricaoApoiadores" => "Descrição"
  );
 
  return $columns;
}

function apoiadores_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoApoiadores": the_excerpt(); break;
  }
}
?>