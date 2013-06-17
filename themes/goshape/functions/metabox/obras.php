<?php
add_action( 'add_meta_boxes', 'add_obras_custom_metabox' );
	
// Add the Projetos Meta Box
function add_obras_custom_metabox() {
	add_meta_box('lat_lng_obras_metabox', 'Latide e Longitude da Obra no Google Maps', 'lat_lng_obras_metabox', 'obras', 'side', 'default');
    add_meta_box('local_obras_metabox', 'Local da Obra', 'local_obras_metabox', 'obras', 'normal', 'default');
	add_meta_box('circuito_obras_metabox', 'Circuito da Obra', 'circuito_obras_metabox', 'obras', 'normal', 'default');
}

// HTML do Local Circuitos Metabox
function lat_lng_obras_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="lat_obras_meta_noncename" id="lat_obras_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	echo '<input type="hidden" name="lng_obras_meta_noncename" id="lng_obras_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $lat = get_post_meta($post->ID, 'lat_obras_meta', true);
	$lng = get_post_meta($post->ID, 'lng_obras_meta', true);
	
	
	echo 'Latitude:<br /> <input type="text" id="lat_obras_meta" name="lat_obras_meta" style="width:100%;" value="'.$lat.'" /><br /><br />';
	
	echo 'Longitude:<br /> <input type="text" id="lng_obras_meta" name="lng_obras_meta" style="width:100%;" value="'.$lng.'" /><br />';
	
}

// HTML do Local Circuitos Metabox
function local_obras_metabox() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="local_obras_meta_noncename" id="local_obras_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $local = get_post_meta($post->ID, 'local_obras_meta', true);
	
	
	echo '<input type="text" id="local_obras_meta" name="local_obras_meta" style="width:100%;" value="'.$local.'" /><br />';
	
}

// HTML do Circuito das obras Metabox
function circuito_obras_metabox() {
    global $post;
	global $wpdb;
	$querystr = "
		SELECT DISTINCT p.*
		FROM wp_posts p
		WHERE p.post_status = 'publish'
		AND p.post_type = 'circuitos'
	";
	
	$resultados = $wpdb->get_results($querystr, OBJECT);
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="circuito_obras_meta_noncename" id="circuito_obras_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $circuito = get_post_meta($post->ID, 'circuito_obras_meta', true);
	
	echo '<select id="circuito_obras_meta" name="circuito_obras_meta" class="widefat">';
	echo '<option>Escolha um Circuito</option>';
	
	foreach($resultados as $p)
	{
		echo '<option value="'.$p->ID.'">'.$p->post_title.'</option>';
	}
	echo '</select>';
	
	echo '
  	<script type="text/javascript">
		for(i=0; i<document.getElementById("circuito_obras_meta").options.length; i++){
			if(document.getElementById("circuito_obras_meta").options[i].value=="'.$circuito.'"){
				document.getElementById("circuito_obras_meta").options[i].selected = true;		
			}
		}
	</script> 
	';
 
}

// Save the Metabox Data
function wpt_save_obras_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['local_obras_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['circuito_obras_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['lat_obras_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
	if ( !wp_verify_nonce( $_POST['lng_obras_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    $events_meta['local_obras_meta'] = $_POST['local_obras_meta'];
	$events_meta['circuito_obras_meta'] = $_POST['circuito_obras_meta'];
	$events_meta['lat_obras_meta'] = $_POST['lat_obras_meta'];
	$events_meta['lng_obras_meta'] = $_POST['lng_obras_meta'];
	
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
 
add_action('save_post', 'wpt_save_obras_meta', 1, 2); // save the custom fields
?>