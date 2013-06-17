<?php
/*

	POST-TYPE: Realizadores

*/
# Chama a função realizadores_register ao iniciar o paínel admin do WP
add_action('init', 'realizadores_register');

# Registramos método para gerar o novo post-type
function realizadores_register() {
 
	$labels = array(
		'name' => _x('Realizadores', 'post type general name'),
		'singular_name' => _x('Realizador Item', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'realizadores'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/realizadores.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'realizadores' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "realizadores_custom_columns");
add_filter("manage_edit-realizadores_columns", "realizadores_edit_columns");
 
function realizadores_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Realizador",
    "descricaoRealizador" => "Descrição",
	"imagemRealizador" => "Imagem",
	"dataRealizador" => "Data"
  );
 
  return $columns;
}

function realizadores_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoRealizador": the_excerpt(); break;
    case "imagemRealizador": echo get_the_post_thumbnail( $post->ID, 'realizadores-thumb', $attr ); break;
	case "dataRealizador": echo get_the_date(); break;
  }
}
?>