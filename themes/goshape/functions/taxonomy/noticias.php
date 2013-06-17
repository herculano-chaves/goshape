<?php
function noticias_categories_init() {
	// create a new taxonomy
	register_taxonomy(
		'noticias_categories',
		'noticias',
		array(
			'label' => __( 'Categorias' ),
			'rewrite' => array( 'slug' => 'categoria' ),
			'hierarchical' => true,
			'query_var' => true
		)
	);
}
add_action( 'init', 'noticias_categories_init' );
?>