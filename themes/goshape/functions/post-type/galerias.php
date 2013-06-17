<?php
/*

	POST-TYPE: Galerias

*/
# Chama a função galerias_register ao iniciar o paínel admin do WP
add_action('init', 'galerias_register');

# Registramos método para gerar o novo post-type
function galerias_register() {
 
	$labels = array(
		'name' => _x('Galerias', 'post type general name'),
		'singular_name' => _x('Galeria', 'post type singular name'),
		'add_new' => _x('Add Nova Galeria', 'galerias'),
		'add_new_item' => __('Add Nova Galeria'),
		'edit_item' => __('Editar Galeria'),
		'new_item' => __('Nova Galeria'),
		'view_item' => __('Ver Galeria'),
		'search_items' => __('Procurar Galeria'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/galerias.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'galerias' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "galerias_custom_columns");
add_filter("manage_edit-galerias_columns", "galerias_edit_columns");
 
function galerias_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Galerias",
    "descricaoGalerias" => "Descrição"
  );
 
  return $columns;
}

function galerias_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoGalerias": the_excerpt(); break;
  }
}
?>