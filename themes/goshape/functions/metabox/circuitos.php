<?php
add_action( 'add_meta_boxes', 'add_circuitos_custom_metabox' );
	
// Add the Projetos Meta Box
function add_circuitos_custom_metabox() {
	add_meta_box('destaque_circuitos_metabox', 'Destaque', 'destaque_circuitos_metabox', 'circuitos', 'side', 'default');
    add_meta_box('data_local_circuitos_meta', 'Local do Circuito', 'data_local_circuitos_meta', 'circuitos', 'normal', 'default');
	add_meta_box('curador_circuitos_metabox', 'Curador do Circuito', 'curador_circuitos_metabox', 'circuitos', 'normal', 'default');
}

// HTML do Local Circuitos Metabox
function destaque_circuitos_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="destaque_circuitos_meta_noncename" id="destaque_circuitos_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $destaque = get_post_meta($post->ID, 'destaque_circuitos_meta', true);
	
	echo '<select id="destaque_circuitos_meta" name="destaque_circuitos_meta" class="widefat">';
	echo '<option value="Não">Não</option>';
	echo '<option value="Sim">Sim</option>';	
	echo '</select>';
	
	echo '
  	<script type="text/javascript">
		for(i=0; i<document.getElementById("destaque_circuitos_meta").options.length; i++){
			if(document.getElementById("destaque_circuitos_meta").options[i].value=="'.$destaque.'"){
				document.getElementById("destaque_circuitos_meta").options[i].selected = true;		
			}
		}
	</script> 
	';
	
}

// HTML do Local Circuitos Metabox
function data_local_circuitos_meta() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="local_circuitos_meta_noncename" id="local_circuitos_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $local = get_post_meta($post->ID, 'local_circuitos_meta', true);
	
	echo '<input type="text" id="local_circuitos_meta" name="local_circuitos_meta" style="width:100%;" value="'.$local.'" /><br />';
	
}

// HTML do Projetos Metabox
function curador_circuitos_metabox() {
    global $post;
	global $wpdb;
	$querystr = "
		SELECT DISTINCT p.*
		FROM wp_posts p
		WHERE p.post_status = 'publish'
		AND p.post_type = 'curadores'
	";
	
	$resultados = $wpdb->get_results($querystr, OBJECT);
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="curador_circuitos_meta_noncename" id="curador_circuitos_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $curador = get_post_meta($post->ID, 'curador_circuitos_meta', true);
	
	echo '<select id="curador_circuitos_meta" name="curador_circuitos_meta" class="widefat">';
	echo '<option>Escolha um Curador</option>';
	
	foreach($resultados as $p)
	{
		echo '<option value="'.$p->ID.'">'.$p->post_title.'</option>';
	}
	echo '</select>';
	
	echo '
  	<script type="text/javascript">
		for(i=0; i<document.getElementById("curador_circuitos_meta").options.length; i++){
			if(document.getElementById("curador_circuitos_meta").options[i].value=="'.$curador.'"){
				document.getElementById("curador_circuitos_meta").options[i].selected = true;		
			}
		}
	</script> 
	';
 
}

// Save the Metabox Data
function wpt_save_circuitos_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['destaque_circuitos_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
    if ( !wp_verify_nonce( $_POST['local_circuitos_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['curador_circuitos_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 	
	$events_meta['destaque_circuitos_meta'] = $_POST['destaque_circuitos_meta'];
    $events_meta['local_circuitos_meta'] = $_POST['local_circuitos_meta'];
	$events_meta['curador_circuitos_meta'] = $_POST['curador_circuitos_meta'];
	
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
 
add_action('save_post', 'wpt_save_circuitos_meta', 1, 2); // save the custom fields
?>