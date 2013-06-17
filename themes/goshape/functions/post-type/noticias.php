<?php
/*

	POST-TYPE: Notícias

*/
# Chama a função noticias_register ao iniciar o paínel admin do WP
add_action('init', 'noticias_register');

# Registramos método para gerar o novo post-type
function noticias_register() {
 
	$labels = array(
		'name' => _x('Notícias', 'post type general name'),
		'singular_name' => _x('Notícia', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'noticias'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/recursos/images/post-type-thumb/noticias.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'excerpt', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'noticias' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "noticias_custom_columns");
add_filter("manage_edit-noticias_columns", "noticias_edit_columns");
 
function noticias_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Noticias",
	"chamadaNoticias" => "Chamada",
    "descricaoNoticias" => "Descrição",
	"dataNoticias" => "Data"
  );
 
  return $columns;
}

function noticias_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoNoticias": the_excerpt(); break;
    case "dataNoticias": echo get_the_date(); break;
	case "chamadaNoticias": echo get_post_meta($post->ID, 'chamada_noticias_meta', true);
  }
}
?>