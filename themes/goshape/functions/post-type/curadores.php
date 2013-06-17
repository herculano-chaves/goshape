<?php
/*

	POST-TYPE: Curadores

*/
# Chama a função curadores_register ao iniciar o paínel admin do WP
add_action('init', 'curadores_register');

# Registramos método para gerar o novo post-type
function curadores_register() {
 
	$labels = array(
		'name' => _x('Curadores', 'post type general name'),
		'singular_name' => _x('Curador', 'post type singular name'),
		'add_new' => _x('Add Novo Curador', 'curadores'),
		'add_new_item' => __('Add Novo Curador'),
		'edit_item' => __('Editar Curador'),
		'new_item' => __('Novo Curador'),
		'view_item' => __('Ver Curador'),
		'search_items' => __('Procurar Curador'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/curadores.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'curadores' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "curadores_custom_columns");
add_filter("manage_edit-curadores_columns", "curadores_edit_columns");
 
function curadores_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Curadores",
    "descricaoCuradores" => "Descrição",
	"imagemCuradores" => "Imagem"
  );
 
  return $columns;
}

function curadores_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "descricaoCuradores": the_excerpt(); break;
    case "imagemCuradores": echo get_the_post_thumbnail( $post->ID, 'curadores-thumb', $attr ); break;
  }
}
?>