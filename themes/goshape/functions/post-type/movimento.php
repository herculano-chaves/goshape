<?php
/*

	POST-TYPE: Movimento

*/
# Chama a função movimento_register ao iniciar o paínel admin do WP
add_action('init', 'movimento_register');

# Registramos método para gerar o novo post-type
function movimento_register() {
 
	$labels = array(
		'name' => _x('Movimento', 'post type general name'),
		'singular_name' => _x('Movimento Item', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'movimento'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/movimento.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'excerpt', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'movimento' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "movimento_custom_columns");
add_filter("manage_edit-movimento_columns", "movimento_edit_columns");
 
function movimento_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Movimento",
    "descricaoMovimento" => "Descrição",
	"imagemMovimento" => "Imagem",
	"dataMovimento" => "Data"
  );
 
  return $columns;
}

function movimento_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoMovimento": the_excerpt(); break;
    case "imagemMovimento": echo get_the_post_thumbnail( $post->ID, 'noticias-thumb-home', $attr ); break;
	case "dataMovimento": echo get_the_date(); break;
  }
}
?>