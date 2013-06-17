<?php
add_action( 'add_meta_boxes', 'add_link_meta_vitrine_programacaobox' );
	
// Add the Projetos Meta Box
function add_link_meta_vitrine_programacaobox() {
    add_meta_box('link_meta_vitrine_programacao', 'URL de destino', 'link_meta_vitrine_programacao', 'vitrine_programacao', 'normal', 'default');
}

// HTML do Projetos Metabox
function link_meta_vitrine_programacao() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="link_meta_vitrine_programacao_noncename" id="link_meta_vitrine_programacao_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $link = get_post_meta($post->ID, 'link_vitrine_programacao', true);
	$linkTipo = get_post_meta($post->ID, 'link_tipo_vitrine_programacao', true);
	
	echo '<p><strong>Exemplo Link interno</strong><br />
		  	Pegar o NAME do Link Permanente do post que fica abaixo do título do mesmo - '.get_bloginfo( 'wpurl' ).'/<span style="background:#FFFB66;">meu-post-ou-pagina/</span>
		  </p>';
		  
	echo '<p><strong>Exemplo Link externo</strong>
		  	- http://www.google.com
		  </p>';
	
	echo '<input type="text" id="link_vitrine_programacao" name="link_vitrine_programacao" style="width:500px;" value="'.$link.'" />';
	
	echo '
		  <p>
			  <input type="radio" name="link_tipo_vitrine_programacao" value="interno"> Link interno &nbsp;&nbsp;&nbsp;&nbsp;
			  <input type="radio" name="link_tipo_vitrine_programacao" value="externo"> Link externo
		  </p>
		  ';
		  
	echo '
  	<script type="text/javascript">
		var itens = document.getElementsByName("link_tipo_vitrine_programacao");
		for(i=0; i<itens.length; i++){
			if(itens[i].value == "'.$linkTipo.'"){
				itens[i].checked = true;		
			}
		}
	</script> 
	';
	 
}

// Save the Metabox Data
function wpt_save_link_meta_vitrine_programacao($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['link_meta_vitrine_programacao_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    $events_meta['link_vitrine_programacao'] = $_POST['link_vitrine_programacao'];
	$events_meta['link_tipo_vitrine_programacao'] = $_POST['link_tipo_vitrine_programacao'];
 
    // Add values of $events_meta as custom fields
 
    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
 
}
 
add_action('save_post', 'wpt_save_link_meta_vitrine_programacao', 1, 2); // save the custom fields
?>