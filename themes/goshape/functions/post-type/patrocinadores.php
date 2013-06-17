<?php
/*

	POST-TYPE: Patrocinadores

*/
# Chama a função patrocinadores_register ao iniciar o paínel admin do WP
add_action('init', 'patrocinadores_register');

# Registramos método para gerar o novo post-type
function patrocinadores_register() {
 
	$labels = array(
		'name' => _x('Patrocinadores', 'post type general name'),
		'singular_name' => _x('Patrocinador', 'post type singular name'),
		'add_new' => _x('Add Novo Patrocinador', 'patrocinadores'),
		'add_new_item' => __('Add Novo Patrocinador'),
		'edit_item' => __('Editar Patrocinador'),
		'new_item' => __('Novo Patrocinador'),
		'view_item' => __('Ver Patrocinador'),
		'search_items' => __('Procurar Patrocinador'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/patrocinadores.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'patrocinadores' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "patrocinadores_custom_columns");
add_filter("manage_edit-patrocinadores_columns", "patrocinadores_edit_columns");
 
function patrocinadores_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Patrocinadores",
    "descricaoPatrocinadores" => "Descrição"
  );
 
  return $columns;
}

function patrocinadores_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoPatrocinadores": the_excerpt(); break;
  }
}
?>