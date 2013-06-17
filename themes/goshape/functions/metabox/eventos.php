<?php
add_action( 'add_meta_boxes', 'add_eventos_custom_metabox' );
	
// Add the Projetos Meta Box
function add_eventos_custom_metabox() {
    add_meta_box('data_inicio_fim_meta', 'Período do evento', 'data_inicio_fim_meta', 'eventos', 'side', 'default');
}

// HTML do Projetos Metabox
function data_inicio_fim_meta() {
    global $post;
	
	echo "
		<script type='text/javascript'>
		jQuery(document).ready(function() { 
			jQuery( '.datepicker' ).datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true
			});
		});
		</script>
	";
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="data_inicio_meta_noncename" id="data_inicio_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	echo '<input type="hidden" name="data_fim_meta_noncename" id="data_fim_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $data_inicio_meta = get_post_meta($post->ID, 'data_inicio_meta', true);
	$data_fim_meta = get_post_meta($post->ID, 'data_fim_meta', true);
	
	echo 'Data do Início: <input type="text" class="datepicker" id="data_inicio_meta" name="data_inicio_meta" style="width:100px; margin-left:18px; margin-right:20px;" value="'.$data_inicio_meta.'" /><br /><br />';
	
	echo 'Data do Término: <input type="text" class="datepicker" id="data_fim_meta" name="data_fim_meta" style="width:100px;" value="'.$data_fim_meta.'" />';  
	 
}

// Save the Metabox Data
function wpt_save_data_inicio_fim_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['data_inicio_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['data_fim_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    $events_meta['data_inicio_meta'] = $_POST['data_inicio_meta'];
	$events_meta['data_fim_meta'] = $_POST['data_fim_meta'];
 
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
 
add_action('save_post', 'wpt_save_data_inicio_fim_meta', 1, 2); // save the custom fields
?>