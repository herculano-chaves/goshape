<?php
add_action( 'add_meta_boxes', 'add_galerias_custom_metabox' );
	
// Add the Projetos Meta Box
function add_galerias_custom_metabox() {
    add_meta_box('localidade_galerias_metabox', 'Localidade da Galeria', 'localidade_galerias_metabox', 'galerias', 'normal', 'default');
	add_meta_box('programa_galerias_metabox', 'Programa da Galeria', 'programa_galerias_metabox', 'galerias', 'normal', 'default');
	add_meta_box('url_galerias_metabox', 'Url da Galeria', 'url_galerias_metabox', 'galerias', 'normal', 'default');
}

// HTML do Local Galerias Metabox
function localidade_galerias_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="localidade_galerias_meta_noncename" id="localidade_galerias_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $local = get_post_meta($post->ID, 'localidade_galerias_meta', true);
	
	echo '<input type="text" id="localidade_galerias_meta" name="localidade_galerias_meta" style="width:100%;" value="'.$local.'" /><br />';
	
}

// HTML do Programa Galerias Metabox
function programa_galerias_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="programa_galerias_meta_noncename" id="programa_galerias_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $programa = get_post_meta($post->ID, 'programa_galerias_meta', true);
	
	echo '<input type="text" id="programa_galerias_meta" name="programa_galerias_meta" style="width:100%;" value="'.$programa.'" /><br />';
	
}

// HTML do Programa Galerias Metabox
function url_galerias_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="url_galerias_meta_noncename" id="url_galerias_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $url = get_post_meta($post->ID, 'url_galerias_meta', true);
	
	echo '<input type="text" id="url_galerias_meta" name="url_galerias_meta" style="width:100%;" value="'.$url.'" /><br />';
	
}

// Save the Metabox Data
function wpt_save_galerias_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['localidade_galerias_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['programa_galerias_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['url_galerias_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    $events_meta['localidade_galerias_meta'] = $_POST['localidade_galerias_meta'];
	$events_meta['programa_galerias_meta'] = $_POST['programa_galerias_meta'];
	$events_meta['url_galerias_meta'] = $_POST['url_galerias_meta'];
	
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
 
add_action('save_post', 'wpt_save_galerias_meta', 1, 2); // save the custom fields
?>