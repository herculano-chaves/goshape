<?php
/*

	POST-TYPE: Circuitos

*/
# Chama a função circuitos_register ao iniciar o paínel admin do WP
add_action('init', 'circuitos_register');

# Registramos método para gerar o novo post-type
function circuitos_register() {
 
	$labels = array(
		'name' => _x('Circuitos', 'post type general name'),
		'singular_name' => _x('Circuito', 'post type singular name'),
		'add_new' => _x('Add Novo Circuito', 'circuitos'),
		'add_new_item' => __('Add Novo Circuito'),
		'edit_item' => __('Editar Circuito'),
		'new_item' => __('Novo Circuito'),
		'view_item' => __('Ver Circuito'),
		'search_items' => __('Procurar Circuito'),
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
		'menu_icon' => get_bloginfo( 'template_url' ) . '/images/post-type-thumb/circuitos.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 60,
		'supports' => array('title', 'thumbnail', 'editor'),
		//'taxonomies' => array('category') 
	  );
 
	register_post_type( 'circuitos' , $args );
}

# Customização da Tela de Portifolio do admin
add_action("manage_posts_custom_column",  "circuitos_custom_columns");
add_filter("manage_edit-circuitos_columns", "circuitos_edit_columns");
 
function circuitos_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Circuitos",
    "descricaoCircuitos" => "Descrição",
	"curadorCircuito" => "Curador",
	"destaqueCircuito" => "Destaque"
  );
 
  return $columns;
}

function circuitos_custom_columns($column){
  global $post;
  
  $curador = getCurador(get_post_meta($post->ID, 'curador_circuitos_meta', true));
  $destaque = get_post_meta($post->ID, 'destaque_circuitos_meta', true);
  
  if($destaque!='Sim'){ $destaque = 'Não'; }
 
  switch ($column) {
    case "descricaoCircuitos": the_excerpt(); break;
	case "curadorCircuito": echo end($curador)->post_title; break;
	case "destaqueCircuito": echo $destaque; break;
  }
}
?>