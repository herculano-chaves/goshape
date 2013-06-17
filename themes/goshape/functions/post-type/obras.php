<?php
/*

	POST-TYPE: Obras

*/
# Chama a função obras_register ao iniciar o paínel admin do WP
add_action('init', 'obras_register');

# Registramos método para gerar o novo post-type
function obras_register() {
 
	$labels = array(
		'name' => _x('Obras', 'post type general name'),
		'singular_name' => _x('Obra', 'post type singular name'),
		'add_new' => _x('Add Novo Obra', 'obras'),
		'add_new_item' => __('Add Novo Obra'),
		'edit_item' => __('Editar Obra'),
		'new_item' => __('Novo Obra'),
		'view_item' => __('Ver Obra'),
		'search_items' => __('Procurar Obra'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/obras.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'obras' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "obras_custom_columns");
add_filter("manage_edit-obras_columns", "obras_edit_columns");
 
function obras_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Obras",
    "descricaoObras" => "Descrição",
	"circuitoObras" => "Circuito"
  );
 
  return $columns;
}

function obras_custom_columns($column){
  global $post;
 
 $circuito = getCircuito(get_post_meta($post->ID, 'circuito_obras_meta', true));

  switch ($column) {
    case "descricaoObras": the_excerpt(); break;
	case "circuitoObras": echo end($circuito)->post_title; break;
  }
}
?>