<?php
add_action( 'add_meta_boxes', 'add_apoio_institucional_custom_metabox' );
	
// Add the Projetos Meta Box
function add_apoio_institucional_custom_metabox() {
	add_meta_box('url_apoio_institucional_metabox', 'Url do Apoiador', 'url_apoio_institucional_metabox', 'apoio_institucional', 'normal', 'default');
}

// HTML do Programa Galerias Metabox
function url_apoio_institucional_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="url_apoio_institucional_meta_noncename" id="url_apoio_institucional_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $url = get_post_meta($post->ID, 'url_apoio_institucional_meta', true);
	
	echo '<input type="text" id="url_apoio_institucional_meta" name="url_apoio_institucional_meta" style="width:100%;" value="'.$url.'" /><br />';
	
}

// Save the Metabox Data
function wpt_save_apoio_institucional_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['url_apoio_institucional_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
	$events_meta['url_apoio_institucional_meta'] = $_POST['url_apoio_institucional_meta'];
	
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
 
add_action('save_post', 'wpt_save_apoio_institucional_meta', 1, 2); // save the custom fields
?>