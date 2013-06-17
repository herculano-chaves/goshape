<?php
/*

	POST-TYPE: area_restrita

*/
# Chama a função area_restrita_register ao iniciar o paínel admin do WP
add_action('init', 'area_restrita_register');

# Registramos método para gerar o novo post-type
function area_restrita_register() {
 
	$labels = array(
		'name' => _x('Área Restrita', 'post type general name'),
		'singular_name' => _x('Área Restrita', 'post type singular name'),
		'add_new' => _x('Add Novo Área Restrita', 'area_restrita'),
		'add_new_item' => __('Add Novo Área Restrita'),
		'edit_item' => __('Editar Área Restrita'),
		'new_item' => __('Novo Área Restrita'),
		'view_item' => __('Ver Área Restrita'),
		'search_items' => __('Procurar Área Restrita'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/area_restrita.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'area_restrita' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "area_restrita_custom_columns");
add_filter("manage_edit-area_restrita_columns", "area_restrita_edit_columns");
 
function area_restrita_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Área Restrita",
    "descricaoAreaRestrita" => "Descrição"
  );
 
  return $columns;
}

function area_restrita_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoAreaRestrita": the_excerpt(); break;
  }
}
?>