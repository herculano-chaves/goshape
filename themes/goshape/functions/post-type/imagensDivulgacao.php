<?php
/*

	POST-TYPE: ImagensDivulgacao

*/
# Chama a função imagensDivulgacao ao iniciar o paínel admin do WP
add_action('init', 'imagensDivulgacao');

# Registramos método para gerar o novo post-type
function imagensDivulgacao() {
 
	$labels = array(
		'name' => _x('Imagens para divulgação', 'post type general name'),
		'singular_name' => _x('Imagem para divulgação Item', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'imagens_divulgacao'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/imagens_divulgacao.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'excerpt', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'imagens_divulgacao' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "imagens_divulgacao_custom_columns");
add_filter("manage_edit-imagens_divulgacao_columns", "imagens_divulgacao_edit_columns");
 
function imagens_divulgacao_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Imagens",
    "descricaoImagensDivulgacao" => "Descrição",
	"imagemImagensDivulgacao" => "Imagem",
	"dataImagensDivulgacao" => "Data"
  );
 
  return $columns;
}

function imagens_divulgacao_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoImagensDivulgacao": the_excerpt(); break;
    case "imagemImagensDivulgacao": echo get_the_post_thumbnail( $post->ID, 'noticias-thumb-home', $attr ); break;
	case "dataImagensDivulgacao": echo get_the_date(); break;
  }
}
?>