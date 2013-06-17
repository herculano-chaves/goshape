<?php
/*

	POST-TYPE: Vitrine Programação

*/
# Chama a função vitrine_programacao_register ao iniciar o paínel admin do WP
add_action('init', 'vitrine_programacao_register');

# Registramos método para gerar o novo post-type
function vitrine_programacao_register() {
 
	$labels = array(
		'name' => _x('Vitrine Programação', 'post type general name'),
		'singular_name' => _x('Vitrine Item', 'post type singular name'),
		'add_new' => _x('Add Novo Item', 'vitrine_programacao'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/recursos/images/post-type-thumb/vitrine_programacao.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 4,
		'supports' => array('title', 'thumbnail', 'excerpt'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'vitrine_programacao' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "vitrine_programacao_custom_columns");
add_filter("manage_edit-vitrine_programacao_columns", "vitrine_programacao_edit_columns");
 
function vitrine_programacao_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Destaque",
    "descricaoVitrineProgramacao" => "Descrição",
	"imagemVitrineProgramacao" => "Imagem"
  );
 
  return $columns;
}

function vitrine_programacao_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoVitrineProgramacao": the_excerpt(); break;
    case "imagemVitrineProgramacao": echo get_the_post_thumbnail( $post->ID, 'thumbnail', $attr ); break;
  }
}
?>