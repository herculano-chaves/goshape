<?php
/*

	POST-TYPE: Eventos

*/
# Chama a função eventos_register ao iniciar o paínel admin do WP
add_action('init', 'eventos_register');

# Registramos método para gerar o novo post-type
function eventos_register() {
 
	$labels = array(
		'name' => _x('Eventos', 'post type general name'),
		'singular_name' => _x('Evento Item', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'eventos'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/recursos/images/post-type-thumb/eventos.png',
		'rewrite' => array( 'slug' => 'show' ),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'editor', 'excerpt'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'eventos' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "eventos_custom_columns");
add_filter("manage_edit-eventos_columns", "eventos_edit_columns");
 
function eventos_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Evento",
    "descricaoEvento" => "Descrição",
	//"imagemEvento" => "Imagem",
	"dataEvento" => "Data"
  );
 
  return $columns;
}

function eventos_custom_columns($column){
  global $post;
  
  $dataInicio = get_post_meta($post->ID, 'data_inicio_meta', true);
  $dataFim = get_post_meta($post->ID, 'data_fim_meta', true);
 
  switch ($column) {
    case "descricaoEvento": the_excerpt(); break;
    //case "imagemEvento": echo get_the_post_thumbnail( $post->ID, 'noticias-destaque-home', $attr ); break;
	case "dataEvento": echo get_the_date(); break;
  }
}
?>