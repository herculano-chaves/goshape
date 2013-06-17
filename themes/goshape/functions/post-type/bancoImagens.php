<?php
/*

	POST-TYPE: bancoImagens

*/
# Chama a função bancoImagens_register ao iniciar o paínel admin do WP
add_action('init', 'bancoImagens_register');

# Registramos método para gerar o novo post-type
function bancoImagens_register() {
 
	$labels = array(
		'name' => _x('Galeria de Imagens', 'post type general name'),
		'singular_name' => _x('Galeria de Imagens Item', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'banco_imagens'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/banco_imagens.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'excerpt', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'banco_imagens' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "banco_imagens_custom_columns");
add_filter("manage_edit-banco_imagens_columns", "banco_imagens_edit_columns");
 
function banco_imagens_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Galeria de Imagens",
    "descricaoBancodeImagens" => "Descrição",
	"imagemBancodeImagens" => "Imagem",
	"dataBancodeImagens" => "Data"
  );
 
  return $columns;
}

function banco_imagens_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoBancodeImagens": the_excerpt(); break;
    case "imagemBancodeImagens": echo get_the_post_thumbnail( $post->ID, 'noticias-thumb-home', $attr ); break;
	case "dataBancodeImagens": echo get_the_date(); break;
  }
}
?>