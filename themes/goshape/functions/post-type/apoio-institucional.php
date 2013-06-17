<?php
/*

	POST-TYPE: Apoio Institucional

*/
# Chama a função apoio_institucional_register ao iniciar o paínel admin do WP
add_action('init', 'apoio_institucional_register');

# Registramos método para gerar o novo post-type
function apoio_institucional_register() {
 
	$labels = array(
		'name' => _x('Apoio Institucional', 'post type general name'),
		'singular_name' => _x('Apoio', 'post type singular name'),
		'add_new' => _x('Add Novo Apoio', 'apoio_institucional'),
		'add_new_item' => __('Add Novo Apoio'),
		'edit_item' => __('Editar Apoio'),
		'new_item' => __('Novo Apoio'),
		'view_item' => __('Ver Apoio'),
		'search_items' => __('Procurar Apoio'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/apoio_institucional.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'apoio_institucional' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "apoio_institucional_custom_columns");
add_filter("manage_edit-apoio_institucional_columns", "apoio_institucional_edit_columns");
 
function apoio_institucional_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Apoio Institucional",
    "descricaoApoioInstitucional" => "Descrição"
  );
 
  return $columns;
}

function apoio_institucional_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoApoioInstitucional": the_excerpt(); break;
  }
}
?>